<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\User\User;
use DB;

class Certificate extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:certificate {student_id? : 學號}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將本地測驗資料同步到中心資料庫';

    /**
     * 本地資料庫帳號資料
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $users;

    /**
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $certificates = $this->getRemoteData();

        $this->users = User::with(['certificate'])->where('username', 'like', '4%')->get();

        $this->analysis['total'] = $this->users->count();

        $this->syncData($certificates);

        $this->printResult();

        return $this->analysis;
    }

    /**
     * 取得中心檢定資料
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRemoteData()
    {
        return collect(DB::connection('elearn')->table('certificates')->get());
    }

    /**
     * 同步資料
     *
     * @param \Illuminate\Support\Collection $certificates
     * @return void
     */
    protected function syncData($certificates)
    {
        $this->setScoresAttribute();

        $groups = $this->groupUsers($certificates);

        $this->updateCertificates($groups['needUpdate']);
        $this->createCertificates($groups['notExists']);

        $this->analysis['success'] = $this->analysis['created'] + $this->analysis['updated'];
    }

    /**
     * 將各個成績轉為統一格式
     *
     * @return void
     */
    protected function setScoresAttribute()
    {
        $this->users->transform(function (User $item) {
            $scores = [];

            foreach ($item->getRelation('certificate') as $certificate) {
                $scores[$certificate->getRelation('category')->getAttribute('name')] = $certificate->getAttribute('score');
            }

            $item->setAttribute('scores', json_encode($scores));

            return $item;
        });
    }

    /**
     * 將使用者分成 不存在 和 需更新 兩個群組
     *
     * @param \Illuminate\Support\Collection $certificates
     * @return array
     */
    protected function groupUsers($certificates)
    {
        $groups = ['notExists' => [], 'needUpdate' => []];

        foreach ($this->users as $user) {
            $exists = $certificates->search(function ($item) use ($user) {
                return $user->getAttribute('username') === $item->username;
            });

            if (false === $exists) {
                $groups['notExists'][] = $user;
            } else if ($this->shouldUpdate($user, $certificates[$exists])) {
                $groups['needUpdate'][] = $user;
            } else {
                ++$this->analysis['notAffect'];
            }
        }

        return $groups;
    }

    /**
     * 判斷該使用者的資訊是否需要更新
     *
     * @param User $user
     * @param $certificate
     * @return bool
     */
    protected function shouldUpdate(User $user, $certificate)
    {
        switch (false) {
            case $user->getAttribute('test_count') === $certificate->test_count:
            case $user->getAttribute('passed_score') === $certificate->passed_score:
            case $user->getAttribute('passed_at') === $certificate->passed_at:
            case $user->getAttribute('scores') === $certificate->scores:
                return true;
            default:
                return false;
        }
    }

    /**
     * 新建檢定資料
     *
     * @param array $users
     * @return void
     */
    protected function createCertificates(array $users = [])
    {
        $this->analysis['create'] = count($users);

        foreach ($users as $user) {
            DB::connection('elearn')->table('certificates')
                ->insert(array_merge(['username' => $user->getAttribute('username')], $this->commonFields($user)))
                ? ++$this->analysis['created']
                : ++$this->analysis['fail'];
        }
    }

    /**
     * 更新檢定資料
     *
     * @param array $users
     * @return void
     */
    protected function updateCertificates(array $users = [])
    {
        $this->analysis['update'] = count($users);

        foreach ($users as $user) {
            DB::connection('elearn')->table('certificates')
                ->where('username', $user->getAttribute('username'))
                ->update($this->commonFields($user))
                ? ++$this->analysis['updated']
                : ++$this->analysis['fail'];
        }
    }

    /**
     * 取得通用欄位值
     *
     * @param User $user
     * @return array
     */
    protected function commonFields(User $user)
    {
        return [
            'test_count' => $user->getAttribute('test_count'),
            'passed_score' => $user->getAttribute('passed_score'),
            'passed_at' => $user->getAttribute('passed_at'),
            'scores' => $user->getAttribute('scores'),
        ];
    }
}
