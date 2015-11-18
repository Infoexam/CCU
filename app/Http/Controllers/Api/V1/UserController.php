<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'show']);

        $this->middleware('auth:admin', ['except' => ['show']]);
    }

    /**
     * 查詢使用者
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        //

        return response()->json();
    }

    /**
     * 新增使用者
     *
     * @param Requests\UserRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Requests\UserRequest $request)
    {
        // 密碼 hash
        $request->merge(['password' => bcrypt($request->input('password'))]);

        User::create($request->only(['username', 'password', 'name', 'email']));

        return $this->ok();
    }

    /**
     * 取得指定使用者資料
     *
     * @param Request $request
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     * @throws AccessDeniedHttpException
     */
    public function show(Request $request, $username)
    {
        // 檢查是否為查詢自己帳號，如為管理原則直接通過檢查
        if ($request->user()->hasRole(['admin'])) {
            $user = User::where('username', '=', $username)->firstOrFail();
        } else if ($request->user()->getAttribute('username') !== $username) {
            throw new AccessDeniedHttpException;
        } else {
            $user = $request->user();
        }

        $user->load(['certificate' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'category_id', 'score', 'free']);
        }, 'department', 'gender', 'grade']);

        return response()->json($user);
    }

    /**
     * 更新指定使用者資料
     *
     * @param Requests\UserRequest $request
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Requests\UserRequest $request, $username)
    {
        $user = User::with(['certificate'])->where('username', '=', $username)->firstOrFail();

        // 更新密碼、姓名、信箱
        ! $request->has('password') ?: $user->setAttribute('password', bcrypt($request->input('password')));
        ! $request->has('name') ?: $user->setAttribute('name', $request->input('name'));
        ! $request->has('email') ?: $user->setAttribute('email', $request->input('email'));

        // 更新免費次數
        foreach ($request->input('free', []) as $key => $value) {
            foreach ($user->getRelation('certificate') as $certificate) {
                if ($certificate->getAttribute('id') === $key) {
                    $certificate->setAttribute('free', $value);
                }
            }
        }

        // 儲存所有資料到資料庫
        $user->push();

        return $this->ok();
    }
}
