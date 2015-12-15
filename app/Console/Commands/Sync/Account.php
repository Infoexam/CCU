<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\General\Category;
use App\Infoexam\User\Certificate;
use App\Infoexam\User\User;
use DB;
use Hash;
use Illuminate\Support\Collection;
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
    protected $description = '將中心的帳號資料同步到本地資料庫';

    /**
     * Local database accounts.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $accounts;

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
        $accounts = collect($this->getRemoteData());

        $this->accounts = User::with(['department', 'grade'])->get();

        $this->analysis['total'] = $accounts->count();

        $this->syncData($accounts);

        $this->printResult();

        return $this->analysis;
    }

    /**
     * 取得中心帳號
     *
     * @return array
     */
    protected function getRemoteData()
    {
        $db = DB::connection('elearn')->table('std_info');

        // 指定帳號
        if (null !== $this->argument('student_id')) {
            $db->where('std_no', '=', $this->argument('student_id'));
        }

        return $this->trimData($db->get());
    }

    /**
     * 同步資料
     *
     * @param \Illuminate\Support\Collection $accounts
     */
    protected function syncData($accounts)
    {
        $groups = $this->groupAccounts($accounts);

        $this->updateAccounts($groups->get('needUpdate'));
        $this->createAccounts($groups->get('notExists'));

        $this->analysis['success'] = $this->analysis['created'] + $this->analysis['updated'];
    }

    /**
     * 將帳號分成 不存在 和 需更新 兩個群組
     *
     * @param $accounts
     * @return Collection
     */
    protected function groupAccounts($accounts)
    {
        $groups = ['notExists' => [], 'needUpdate' => []];

        foreach ($accounts as $account) {
            $exists = $this->accounts->search(function (User $item) use ($account) {
                return $item->getAttribute('username') === $account->std_no;
            });

            if (false === $exists) {
                $groups['notExists'][] = $account;
            } else if ($this->shouldUpdate($this->accounts[$exists], $account)) {
                $groups['needUpdate'][] = $account;
            } else {
                ++$this->analysis['notAffect'];
            }
        }

        return collect($groups);
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
            //case Hash::check($account->user_pass, $user->getAttribute('password')): // 極花效能
            case $user->getAttribute('name') === trim($account->name):
            case $user->getAttribute('email') === trim($account->email):
            case $user->getAttribute('class') === trim($account->now_class):
            case $user->getRelation('department')->getAttribute('name') === trim($account->deptcd):
            case isset($this->gradesTable[$account->now_grade]):
            case $user->getRelation('grade')->getAttribute('name') === ($this->gradesTable[$account->now_grade]):
                return true;
            default:
                return false;
        }
    }

    /**
     * 創建帳號
     *
     * @param array $accounts
     */
    protected function createAccounts(array $accounts = [])
    {
        $this->analysis['create'] = count($accounts);

        foreach ($accounts as $account) {
            $user = User::create(array_merge(['username' => $account->std_no], $this->commonFields($account)));

            foreach (Category::getCategories('exam.category') as $category) {
                $user->certificate()->save(new Certificate(['category_id' => $category->getAttribute('id')]));
            }

            $user->exists ? ++$this->analysis['created'] : ++$this->analysis['fail'];
        }
    }

    /**
     * 更新帳號資料
     *
     * @param array $accounts
     */
    protected function updateAccounts(array $accounts = [])
    {
        $this->analysis['update'] = count($accounts);

        foreach ($accounts as $account) {
            $success = User::where('username', '=', $account->std_no)->update($this->commonFields($account));

            $success ? ++$this->analysis['updated'] : ++$this->analysis['fail'];
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
        return [
            'password' => bcrypt($account->user_pass),
            'name' => $account->name,
            'email' => $account->email,
            'social_security_number' => $account->id_num,
            'gender_id' => Category::getCategories('user.gender', [
                'name' => ('F' == $account->sex) ? 'female' : 'male',
                'firstId' => true,
            ]),
            'department_id' => Category::getCategories('user.department', [
                'name' => $account->deptcd,
                'firstId' => true,
            ]),
            'grade_id' => Category::getCategories('user.grade', [
                'name' => isset($this->gradesTable[$account->now_grade]) ? $this->gradesTable[$account->now_grade] : 'deferral',
                'firstId' => true,
            ]),
            'class' => $account->now_class,
        ];
    }
}
