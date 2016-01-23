<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UserRequest;
use App\Infoexam\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends ApiController
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
        $users = User::with(['department']);

        if ($request->has('username')) {
            $users = $users->where('username', 'like', '%' . $request->input('username') . '%');
        }

        if ($request->has('name')) {
            $users = $users->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('department')) {
            $users = $users->where('department_id', $request->input('department'));
        }

        if ($request->has('code')) {
            $users = $users->whereHas('_lists', function (Builder $query) use ($request) {
                $query->where('code', $request->input('code'));
            });
        }

        return $this->setData($users->get())->responseOk();
    }

    /**
     * 新增使用者
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        // 密碼 hash
        $request->merge(['password' => bcrypt($request->input('password'))]);

        $user = User::create($request->only(['username', 'password', 'name', 'email']));

        if (! $user->exists) {
            return $this->responseUnknownError();
        }

        return $this->setData($user)->responseCreated();
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
            $user = User::with(['roles' => function (BelongsToMany $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'name']);
            }])->where('username', $username)->first();

            if (is_null($user)) {
                return $this->responseNotFound();
            }
        } else if ($request->user()->getAttribute('username') !== $username) {
            throw new AccessDeniedHttpException;
        } else {
            $user = $request->user();
        }

        $user->load(['certificate' => function (HasMany $relation) {
            $relation->getQuery()->getQuery()->select(['id', 'user_id', 'category_id', 'score', 'free']);
        }, 'department', 'gender', 'grade']);

        return $this->setData($user)->responseOk();
    }

    /**
     * 更新指定使用者資料
     *
     * @param UserRequest $request
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $username)
    {
        $user = User::with(['certificate'])->where('username', $username)->first();

        if (is_null($user)) {
            return $this->responseNotFound();
        }

        // 更新密碼、姓名、信箱
        ! $request->has('password') ?: $user->setAttribute('password', bcrypt($request->input('password')));
        ! $request->has('name') ?: $user->setAttribute('name', $request->input('name'));
        ! $request->has('email') ?: $user->setAttribute('email', $request->input('email'));

        // 更新免費次數
        foreach ($user->getRelation('certificate') as $certificate) {
            $value = $request->input('free.' . $certificate->getAttribute('category_id'));

            if (! is_null($value)) {
                $certificate->update(['free' => $value]);
            }
        }

        // 更新帳號所屬角色（身份）
        $user->roles()->sync($request->input('roles', []));

        // 儲存所有資料到資料庫
        $user->save();

        return $this->setData($user)->responseOk();
    }
}
