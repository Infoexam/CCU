<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $username = 'test_' . str_random(4);
        $password = str_random(4);

        // 產生測試帳號
        $user = factory(\App\Infoexam\User\User::class)->create([
            'username' => $username,
            'password' => bcrypt($password, ['rounds' => 10]),
        ]);

        $oldPassword = $user->getAttribute('password');

        // 錯誤的帳密
        $this->call('POST', route('api.v1.auth.signIn'), [
            'username' => $username,
            'password' => 'YO~',
        ]);
        $this->assertResponseStatus(422);

        // 正確的帳密
        $this->call('POST', route('api.v1.auth.signIn'), [
            'username' => $username,
            'password' => $password,
        ]);
        $this->assertResponseOk();

        // 檢驗是否有重新 hash 密碼
        $this->assertNotEquals(Auth::user()->getAttribute('password'), $oldPassword);
    }
}