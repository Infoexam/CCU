<?php

use App\Infoexam\Exam\Set;
use App\Infoexam\General\Category;
use App\Infoexam\User\Role;
use App\Infoexam\User\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExamSetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@index
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', route('api.v1.exam.sets.index'));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@store
     *
     * @return void
     */
    public function testStore()
    {
        $route = route('api.v1.exam.sets.store');

        // 產生測試帳號
        $adminRoleId = Role::where('name', '=', 'admin')->first(['id'])->getAttribute('id');

        // 一般帳號
        $userNormal = factory(User::class)->create();

        // admin 帳號
        $userAdmin = factory(User::class)->create();
        $userAdmin->roles()->sync([$adminRoleId]);


        // 未登入下新增題庫
        Auth::logout();
        $this->call('POST', $route);
        $this->assertResponseStatus(401);

        // 一般帳號新增題庫
        Auth::loginUsingId($userNormal->getAttribute('id'));
        $this->call('POST', $route);
        $this->assertResponseStatus(403);

        // admin 帳號新增題庫
        Auth::loginUsingId($userAdmin->getAttribute('id'));
        $this->call('POST', $route, [
            'name' => 'test_' . str_random(4),
            'category_id' => Category::where('category', '=', 'exam.category')->first(['id'])->getAttribute('id'),
            'enable' => '1',
        ]);
        $this->assertResponseOk();
    }

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@show
     *
     * @return void
     */
    public function testShow()
    {
        $setId = factory(Set::class)->create()->getAttribute('id');

        // 正常查詢
        $response = $this->call('GET', route('api.v1.exam.sets.show', [
            'sets' => $setId,
        ]));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());

        // 查詢不存在的資料
        $this->call('GET', route('api.v1.exam.sets.show', ['sets' => $setId + 100]));
        $this->assertResponseStatus(404);
    }

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@update
     *
     * @return void
     */
    public function testUpdate()
    {
        // 產生測試帳號
        $user = factory(User::class)->create();
        $user->roles()->sync([Role::where('name', '=', 'admin')->first(['id'])->getAttribute('id')]);

        // 產生測試題庫
        $set = factory(Set::class)->create();

        $route = route('api.v1.exam.sets.update', ['sets' => $set->getAttribute('id')]);

        // 未登入下更新題庫
        Auth::logout();
        $this->call('PATCH', $route);
        $this->assertResponseStatus(401);

        Auth::loginUsingId($user->getAttribute('id'));

        // 更新不存在的題庫
        $this->call('PATCH', route('api.v1.exam.sets.update', ['sets' => $set->getAttribute('id') + 100]), [
            'name' => '1',
            'category_id' => $set->getAttribute('category_id'),
            'enable' => 1,
        ]);
        $this->assertResponseStatus(404);

        // 更新測試題庫
        $this->call('PATCH', $route, [
            'name' => 'test_' . str_random(4),
            'category_id' => $set->getAttribute('category_id'),
            'enable' => $set->getAttribute('enable'),
        ]);
        $this->assertResponseOk();

        // 確認資料是否更新
        $setUpdated = Set::find($set->getAttribute('id'));
        $this->assertNotEquals($set, $setUpdated);
        $this->assertNotSame($set->getAttribute('name'), $setUpdated->getAttribute('name'));
        $this->assertSame($set->getAttribute('category_id'), $setUpdated->getAttribute('category_id'));
        $this->assertSame($set->getAttribute('enable'), $setUpdated->getAttribute('enable'));
    }

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@destroy
     *
     * @return void
     */
    public function testDestroy()
    {
        // 產生測試資料
        $setId = factory(Set::class)->create()->getAttribute('id');

        // 刪除測試資料
        $this->call('DELETE', route('api.v1.exam.sets.destroy', ['sets' => $setId]));
        $this->assertResponseOk();

        // 確認查詢結果為 null
        $this->assertNull(Set::find($setId));

        // 刪除不存在的資料
        $this->call('DELETE', route('api.v1.exam.sets.destroy', ['sets' => $setId]));
        $this->assertResponseStatus(404);
    }

    /**
     * test for \App\Http\Controller\Api\V1\ExamSetController@allQuestions
     *
     * @return void
     */
    public function testAllQuestions()
    {
        $response = $this->call('GET', route('api.v1.exam.sets.allQuestions'));
        $this->assertResponseOk();
        $this->assertJson($response->getContent());
    }
}
