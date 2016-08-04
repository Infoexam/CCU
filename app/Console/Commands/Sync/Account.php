<?php

namespace App\Console\Commands\Sync;

use App\Accounts\Certificate;
use App\Accounts\User;
use App\Categories\Category;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Ko\ProcessManager;
use Ramsey\Uuid\Uuid;

class Account extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將學籍帳號資料同步到本地資料庫';

    /**
     * Unique identity for current process string.
     *
     * @var string
     */
    protected $uuid = 'sync:account';

    /**
     * Mapping departments code to id.
     *
     * @var array
     */
    protected $departments = [];

    /**
     * Mapping grades code to id.
     *
     * @var array
     */
    protected $grades = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->uuid = Uuid::uuid4();

        $this->mapping();

        $this->fetch()
            ->each(function ($group, $method) {
                $this->{$method}($group);
            });

        $this->certificates();
    }

    /**
     * Create departments and grades mapping data.
     *
     * @return void
     */
    protected function mapping()
    {
        $this->departments = Category::getCategories('user.department')
            ->pluck('id', 'name')
            ->toArray();

        $this->grades = Category::getCategories('user.grade')
            ->pluck('name', 'id')
            ->transform(function ($name) {
                static $mapping = [
                    'freshman' => '1',
                    'sophomore' => '2',
                    'junior' => '3',
                    'senior' => '4',
                ];

                return $mapping[$name] ?? '5';
            })
            ->unique()
            ->flip()
            ->toArray();
    }

    /**
     * Get data and group by create or update.
     *
     * @return Collection
     */
    protected function fetch()
    {
        $this->info('Fetching data...');

        $local = $this->local();

        return $this->remote()
            ->map(function (array $user) use ($local) {
                $model = $offset = $local->where('username', $user['std_no'])->keys()->first();

                if (! is_null($offset)) {
                    $model = $local->get($offset);

                    $local->forget($offset);
                }

                $user['_type'] = is_null($model) ? 'create' : 'update';
                $user['_model'] = $model;

                return $user;
            })
            ->groupBy('_type');
    }

    /**
     * Get synchronous data.
     *
     * @return Collection
     */
    protected function remote()
    {
        $data = DB::connection('elearn')->table('std_info')->get();

        $data = $data instanceof Collection ? $data : new Collection($data);

        return $this->trim($data);
    }

    /**
     * Get synchronized data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function local()
    {
        return User::all();
    }

    /**
     * Create new users.
     *
     * @param Collection $users
     *
     * @return void
     */
    protected function create(Collection $users)
    {
        $this->info('Sync new accounts...');

        $manager = new ProcessManager();

        $count = $users->chunk(intval(ceil($users->count() / 8)))
            ->each(function ($users, $index) use ($manager) {
                $manager->fork(function () use ($users, $index) {
                    $this->parallel($users, $index);
                });
            })
            ->count();

        $manager->wait();

        for ($i = 0; $i < $count; ++$i) {
            $chucks = Cache::pull("{$this->uuid}|create|{$i}", []);

            foreach (array_chunk($chucks, 1000) as $chuck) {
                User::insert($chuck);
            }
        }
    }

    /**
     * Update exist users.
     *
     * @param Collection $users
     *
     * @return void
     */
    protected function update(Collection $users)
    {
        $this->info('Sync exist accounts...');

        $users->each(function (array $user) {
            if ($this->isModified($user)) {
                $user['_model']->update($this->user($user, true));
            }
        });
    }

    /**
     * Check remote data is same as local or not.
     *
     * @param array $user
     *
     * @return bool
     */
    protected function isModified(array $user)
    {
        $model = $user['_model'];

        return ! (
            $user['name'] === $model->getAttribute('name') &&
            $user['email'] === $model->getAttribute('email') &&
            $user['sex'] === $model->getAttribute('gender') &&
            ($this->departments[$user['deptcd']] ?? $this->departments['0000']) == $model->getAttribute('department_id') &&
            ($this->grades[$user['now_grade']] ?? $this->grades[5]) == $model->getAttribute('grade_id') &&
            $user['now_class'] === $model->getAttribute('class')
        );
    }

    /**
     * Parallel compute the user data, bcrypt is time-consuming.
     *
     * @param Collection $users
     * @param int $index
     * @param bool $update
     *
     * @return void
     */
    protected function parallel(Collection $users, $index, $update = false)
    {
        $data = $users->map(function (array $user) use ($update) {
            return $this->user($user, $update);
        })
            ->toArray();

        Cache::put(implode('|', [
            $this->uuid,
            $update ? 'update' : 'create',
            $index,
        ]), $data, 180);
    }

    /**
     * Get user data according to create or update.
     *
     * @param array $user
     * @param bool $update
     *
     * @return array
     */
    protected function user(array $user, $update = false)
    {
        $info = [
            'name' => $user['name'],
            'email' => $user['email'],
            'gender' => $user['sex'],
            'department_id' => $this->departments[$user['deptcd']] ?? $this->departments['0000'],
            'grade_id' => $this->grades[$user['now_grade']] ?? $this->grades[5],
            'class' => $user['now_class'],
        ];

        if (! $update) {
            $now = Carbon::now()->toDateTimeString();

            $info['username'] = $user['std_no'];
            $info['role'] = starts_with($user['std_no'], '4') ? 'under' : 'graduate';
            $info['password'] = bcrypt($user['user_pass']);
            $info['created_at'] = $now;
            $info['updated_at'] = $now;
        }

        return $info;
    }

    /**
     * Sync user certificates.
     *
     * @return void
     */
    protected function certificates()
    {
        $this->info('Sync certificates...');

        Category::getCategories('exam.category')
            ->each(function (Category $category) {
                $new = array_map(function ($userId) use ($category) {
                    $now = Carbon::now()->toDateTimeString();

                    return [
                        'user_id' => $userId,
                        'category_id' => $category->getAttribute('id'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }, $this->userIdsForCreate($category->getAttribute('id')));

                foreach (array_chunk($new, 1000) as $chuck) {
                    Certificate::insert($chuck);
                }
            });
    }

    /**
     * Get user ids that do not have specific certificate record.
     *
     * @param int $categoryId
     *
     * @return array
     */
    protected function userIdsForCreate($categoryId)
    {
        static $users = null;

        if (is_null($users)) {
            $users = User::where('role', 'under')->get(['id'])->pluck('id')->all();
        }

        return array_diff(
            $users,
            User::whereHas('certificates', function (Builder $query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->get(['id'])->pluck('id')->all()
        );
    }
}
