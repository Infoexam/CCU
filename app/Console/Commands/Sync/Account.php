<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\General\Category;
use App\Infoexam\User\Role;
use App\Infoexam\User\User;
use Carbon\Carbon;
use DB;

class Account extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:account {student_id? : 學號}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將中心帳號資料同步到本地資料庫';

    /**
     * 資料來源
     *
     * @var array
     */
    protected $rs;

    /**
     * 目的地資料
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $rd;

    /**
     * 資料對應表
     *
     * @var array
     */
    protected $mapTable = [
        'gender' => ['F' => 'female', 'M' => 'male'],
        'grade' => ['1' => 'freshman', '2' => 'sophomore', '3' => 'junior', '4' => 'senior']
    ];

    /**
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $this->initMapTable();

        $this->rs = $this->getSourceData();

        $this->rd = User::with(['certificates', 'department', 'gender', 'grade'])->get();

        $this->syncDestinationData($this->groupData());

        return parent::handle();
    }

    /**
     * 初始話 mapTable
     *
     * @return void
     */
    protected function initMapTable()
    {
        $certificates = Category::getCategories('exam.category');

        $this->mapTable['certificates'] = [
            'count' => $certificates->count(),
            'ids' => $certificates->pluck('id')->all(),
        ];

        $this->mapTable['now'] = Carbon::now();
    }

    /**
     * 取得中心帳號資料
     *
     * @return array
     */
    protected function getSourceData()
    {
        $db = DB::connection('elearn')->table('std_info');

        // 指定帳號
        if (! is_null($this->argument('student_id'))) {
            $db->where('std_no', $this->argument('student_id'));
        }

        return $this->trimData($db->get());
    }

    /**
     * 將資料分群
     *
     * @return array
     */
    protected function groupData()
    {
        $groups = ['create' => [], 'update' => [], 'syncCertificates' => []];
        $indexes = $this->rd->pluck('username')->all();

        foreach ($this->rs as $key => $user) {
            $index = array_search($user->std_no, $indexes, true);

            if (false === $index) {
                $groups['create'][] = $key;
            } else if ($this->isDirty($this->rd[$index], $user)) {
                $groups['update'][] = ['rd' => $index, 'rs' => $key];
            } else if ($this->rd[$index]->getRelation('certificates')->count() !== $this->mapTable['certificates']['count']) {
                $groups['syncCertificates'][] = $index;
            } else {
                ++$this->analysis['notAffect'];
            }
        }

        $this->analysis['total'] = count($this->rs);
        $this->analysis['create'] = count($groups['create']);
        $this->analysis['update'] = count($groups['update']) + count($groups['syncCertificates']);

        return $groups;
    }

    /**
     * 判斷帳號資料是否需要更新
     *
     * @param User $rd
     * @param $rs
     * @return bool
     */
    protected function isDirty(User $rd, $rs)
    {
        switch (false) {
            case $rd->getAttribute('name') === $rs->name:
            case $rd->getAttribute('email') === $rs->email:
            case $rd->getAttribute('ssn') === $rs->id_num:
            case $rd->getRelation('gender')->getAttribute('name') === $this->mapTable['gender'][$rs->sex]:
            case $rd->getRelation('department')->getAttribute('name') === $rs->deptcd:
            case isset($this->mapTable['grade'][$rs->now_grade]):
            case $rd->getRelation('grade')->getAttribute('name') === $this->mapTable['grade'][$rs->now_grade]:
            case $rd->getAttribute('class') === $rs->now_class:
                return true;
            default:
                return false;
        }
    }

    /**
     * 同步資料
     *
     * @param array $data
     */
    protected function syncDestinationData($data)
    {
        $this->create($data['create']);

        $this->update($data['update']);

        $this->syncCertificates($data['syncCertificates']);

        $this->analysis['success'] = $this->analysis['updated'] + $this->analysis['created'];
    }

    /**
     * 新增帳號
     *
     * @param array $indexes
     */
    protected function create(array $indexes)
    {
        $delayInserts = ['certificates' => [], 'roles' => []];
        $roleId = Role::where('name', 'undergraduate')->first()->getAttribute('id');

        foreach ($indexes as $index) {
            $user = User::create(array_merge([
                'username' => $this->rs[$index]->std_no,
                'password' => bcrypt($this->rs[$index]->user_pass),
            ], $this->commonFields($index)));

            if (! $user->exists) {
                ++$this->analysis['fail'];
            } else {
                foreach ($this->mapTable['certificates']['ids'] as $id) {
                    $delayInserts['certificates'][] = [
                        'user_id' => $user->getAttribute('id'),
                        'category_id' => $id,
                        'created_at' => $this->mapTable['now'],
                        'updated_at' => $this->mapTable['now'],
                    ];
                }

                $delayInserts['roles'][] = ['user_id' => $user->getAttribute('id'), 'role_id' => $roleId];
            }
        }

        DB::table('certificates')->insert($delayInserts['certificates']);
        DB::table('role_user')->insert($delayInserts['roles']);

        $this->analysis['created'] = count($delayInserts['roles']);
    }

    /**
     * 更新帳號
     *
     * @param array $indexes
     */
    protected function update(array $indexes)
    {
        foreach ($indexes as $index) {
            $this->rd[$index['rd']]->update($this->commonFields($index['rs']))
                ? ++$this->analysis['updated']
                : ++$this->analysis['fail'];
        }
    }

    /**
     * 更新測驗資料
     *
     * @param array $indexes
     */
    protected function syncCertificates(array $indexes)
    {
        $inserts = [];

        foreach ($indexes as $index) {
            $ids = array_diff(
                $this->mapTable['certificates']['ids'],
                $this->rd[$index]->getRelation('certificates')->pluck('category_id')->all()
            );

            foreach ($ids as $id) {
                $inserts[] = [
                    'user_id' => $this->rd[$index]->getAttribute('id'),
                    'category_id' => $id,
                    'created_at' => $this->mapTable['now'],
                    'updated_at' => $this->mapTable['now'],
                ];
            }
        }

        DB::table('certificates')->insert($inserts);

        $this->analysis['updated'] += count($indexes);
    }

    /**
     * 取得新增與更新共同之欄位
     *
     * @param int $index
     * @return array
     */
    protected function commonFields($index)
    {
        $gradeName = isset($this->mapTable['grade'][$this->rs[$index]->now_grade])
            ? $this->mapTable['grade'][$this->rs[$index]->now_grade]
            : 'deferral';

        return [
            'name' => $this->rs[$index]->name,
            'email' => $this->rs[$index]->email,
            'ssn' => $this->rs[$index]->id_num,
            'gender_id' => Category::getCategories('user.gender', $this->mapTable['gender'][$this->rs[$index]->sex], true),
            'department_id' => Category::getCategories('user.department', $this->rs[$index]->deptcd, true),
            'grade_id' => Category::getCategories('user.grade', $gradeName, true),
            'class' => $this->rs[$index]->now_class,
        ];
    }
}
