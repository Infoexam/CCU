<?php

namespace App\Infoexam\User;

class Auth
{
    /**
     * 嘗試從中心伺服器登入
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function attemptUsingCenter($username, $password)
    {
        if (app()->environment(['local', 'testing'])) {
            return false;
        }

        /*
         * 1. 檢查參數是否正確
         * 2. 確認本地帳號存在
         * 3. 中心驗證帳號
         */
        if (empty($username) || empty($password) || ! is_string($username) || ! is_string($password)) {
            return false;
        } else if (null === ($user = User::where('username', '=', $username)->first(['id', 'password']))) {
            return false;
        } else if (null === ($new = \DB::connection('elearn')->table('std_info')
            ->where('std_no', '=', $username)->where('user_pass', '=', $password)->first(['user_pass']))) {
            return false;
        }

        // 登入並更新本地密碼
        \Auth::loginUsingId($user->getAttribute('id'))->update(['password' => bcrypt($new->user_pass)]);

        return true;
    }

    /**
     * 如果有需要，則重新 hash 密碼並存之
     *
     * @param User $user
     * @param string $oldPassword
     * @return void
     */
    public static function rehashPasswordIfNeeded(User $user, $oldPassword)
    {
        if (\Hash::needsRehash($user->getAttribute('password'))) {
            $user->setAttribute('password', bcrypt($oldPassword));

            $user->save();
        }
    }
}
