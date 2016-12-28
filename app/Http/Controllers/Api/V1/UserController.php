<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Infoexam\Eloquent\Models\User;

class UserController extends Controller
{
    public function search(Request $request, User $user)
    {
        $keyword = $request->input('keyword');

        $user = $user->with(['department']);

        if (str_contains($keyword, ['@'])) {
            $user = $user->where('email', 'like', "%$keyword%");
        } elseif (preg_match('/^\d+$/', $keyword)) {
            $user = $user->where('username', 'like', "{$keyword}%");
        } else {
            $user = $user->where('name', 'like', "%{$keyword}%");
        }

        return $user->paginate(20)->appends(compact('keyword'));
    }

    public function show($username)
    {
        $user = User::with(['department', 'grade', 'certificates', 'certificates.category'])
            ->where('username', $username)
            ->first();

        if (is_null($user)) {
            $this->response->errorNotFound();
        }

        return $user;
    }

    public function update(Request $request, $username)
    {
        $user = User::with([
            'certificates' => function ($query) use ($request) {
                $query->where('category_id', $request->input('category'));
            }])
            ->where('username', $username)
            ->first();

        if (is_null($user) || is_null($certificate = $user->getRelation('certificates')->first())) {
            $this->response->errorNotFound();
        }

        if ('free' === $request->input('type')) {
            $certificate->update([
                'free' => $certificate->getAttribute('free') + intval($request->input('times', 0)),
            ]);
        } elseif ('credit' === $request->input('type')) {
            $certificate->update([
                'score' => intval($certificate->getAttribute('score')) - 999,
            ]);
        }

        return $user->fresh(['department', 'grade', 'certificates', 'certificates.category']);
    }
}
