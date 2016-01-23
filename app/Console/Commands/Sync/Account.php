<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\General\Category;
use App\Infoexam\User\Certificate;
use App\Infoexam\User\Role;
use App\Infoexam\User\User;
use Carbon\Carbon;
use DB;
use stdClass;

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
     * Local database accounts.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $accounts;

    /**
     * Certificate numbers.
     *
     * @var integer
     */
    protected $certificatesCount;

    /**
     * 年級對應表
     *
     * @var array
     */
    protected $gradesTable = [
        '1' => 'freshman',
        '2' => 'sophomore',
        '3' => 'junior',
        '4' => 'senior',
    ];

    /**
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $accounts = $this->getRemoteData();

        $this->accounts = User::with(['certificates', 'department', 'grade'])->get();

        $this->certificatesCount = Category::getCategories('exam.category')->count();

        $this->analysis['total'] = $accounts->count();

        $this->syncData($accounts);

        $this->printResult();

        return $this->analysis;
    }

    /**
     * 取得中心帳號資料
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRemoteData()
    {
        $db = DB::connection('elearn')->table('std_info');

        // 指定帳號
        if (null !== $this->argument('student_id')) {
            $db->where('std_no', $this->argument('student_id'));
        }

        return collect($this->trimData($db->get()));
    }

    /**
     * 同步資料
     *
     * @param \Illuminate\Support\Collection $accounts
     * @return void
     */
    protected function syncData($accounts)
    {
        $groups = $this->groupAccounts($accounts);

        $this->updateAccounts($groups['needUpdate']);
        $this->createAccounts($groups['notExists']);

        $this->analysis['success'] = $this->analysis['created'] + $this->analysis['updated'];
    }

    /**
     * 將帳號分成 不存在 和 需更新 兩個群組
     *
     * @param $accounts
     * @return array
     */
    protected function groupAccounts($accounts)
    {
        $groups = ['notExists' => [], 'needUpdate' => []];

        $indexes = $this->accounts->pluck('username')->all();

        foreach ($accounts as $account) {
            $exists = array_search($account->std_no, $indexes);

            if (false === $exists) {
                $groups['notExists'][] = $account;
            } else if ($this->shouldUpdate($this->accounts[$exists], $account)) {
                $groups['needUpdate'][] = ['remote' => $account, 'local' => $this->accounts[$exists]];
            } else {
                ++$this->analysis['notAffect'];
            }
        }

        return $groups;
    }

    /**
     * 判斷該帳號是否需要更新
     *
     * @param User $user
     * @param $account
     * @return bool
     */
    protected function shouldUpdate(User $user, $account)
    {
        switch (false) {
            case $user->getAttribute('name') === $account->name:
            case $user->getAttribute('email') === $account->email:
            case $user->getAttribute('class') === $account->now_class:
            case $user->getRelation('department')->getAttribute('name') === $account->deptcd:
            case isset($this->gradesTable[$account->now_grade]):
            case $user->getRelation('grade')->getAttribute('name') === ($this->gradesTable[$account->now_grade]):
            case $user->getRelation('certificates')->count() === $this->certificatesCount:
                return true;
            default:
                return false;
        }
    }

    /**
     * 創建帳號
     *
     * @param array $accounts
     * @return void
     */
    protected function createAccounts(array $accounts = [])
    {
        $this->analysis['create'] = count($accounts);

        $certificates = Category::getCategories('exam.category')->pluck('id')->all();

        $role_id = Role::where('name', 'undergraduate')->first()->getAttribute('id');

        $now = Carbon::now();

        $delayInsert = ['certificates' => [], 'roles' => []];

        foreach ($accounts as $account) {
            $user = User::create(array_merge([
                'username' => $account->std_no,
                'password' => bcrypt($account->user_pass),
            ], $this->commonFields($account)));

            if (! $user->exists) {
                ++$this->analysis['fail'];
            } else {
                ++$this->analysis['created'];

                foreach ($certificates as $certificate) {
                    $delayInsert['certificates'][] = [
                        'user_id' => $user->getAttribute('id'),
                        'category_id' => $certificate,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $delayInsert['roles'][] = [
                    'user_id' => $user->getAttribute('id'),
                    'role_id' => $role_id
                ];
            }
        }

        DB::table('certificates')->insert($delayInsert['certificates']);
        DB::table('role_user')->insert($delayInsert['roles']);
    }

    /**
     * 更新帳號資料
     *
     * @param array $accounts
     * @return void
     */
    protected function updateAccounts(array $accounts = [])
    {
        $this->analysis['update'] = count($accounts);

        $categoriesId = Category::getCategories('exam.category')->pluck('id')->all();

        foreach ($accounts as $account) {
            /** @var User $user */
            $user = $account['local'];

            $certificates = array_map(function ($id) {
                return new Certificate(['category_id' => $id]);
            }, array_diff($categoriesId, $user->getRelation('certificates')->pluck('category_id')->all()));

            if (count($certificates) > 0) {
                $user->certificates()->saveMany($certificates);
            }

            $user->update($this->commonFields($account['remote']))
                ? ++$this->analysis['updated']
                : ++$this->analysis['fail'];
        }
    }

    /**
     * 取得帳號通用欄位值
     *
     * @param stdClass $account
     * @return array
     */
    protected function commonFields(stdClass $account)
    {
        $gradeName = isset($this->gradesTable[$account->now_grade])
            ? $this->gradesTable[$account->now_grade]
            : 'deferral';

        return [
            'name' => $account->name,
            'email' => $account->email,
            'ssn' => $account->id_num,
            'gender_id' => Category::getCategories('user.gender', ('F' == $account->sex) ? 'female' : 'male', true),
            'department_id' => Category::getCategories('user.department', $account->deptcd, true),
            'grade_id' => Category::getCategories('user.grade', $gradeName, true),
            'class' => $account->now_class,
        ];
    }
}
