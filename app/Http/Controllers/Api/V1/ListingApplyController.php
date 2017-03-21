<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApplyUncancelableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListingApplyRequest;
use App\Services\ApplyService;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\User;

class ListingApplyController extends Controller
{
    /**
     * @var ApplyService
     */
    private $service;

    /**
     * Constructor.
     *
     * @param ApplyService $service
     */
    public function __construct(ApplyService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $code
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function index($code)
    {
        return $this->service->getList($code, [
            'id', 'user_id', 'listing_id', 'type', 'paid_at',
        ]);
    }

    public function store(ListingApplyRequest $request, $code)
    {
        if (! $request->user()->own('admin')) {
            if (true !== ($result = $this->service->apply($code))) {
                $this->response->errorBadRequest($result);
            }
        } else {
            if (! $request->has('department')) {
                $username = str_replace(["\r\n", "\r", "\n"], PHP_EOL, trim($request->input('username')));

                $users = User::whereIn('username', explode(PHP_EOL, $username))->whereNull('passed_at')->get();
            } else {
                $gradeId = Category::getCategories('user.grade', 'sophomore');

                $users = User::where('department_id', $request->input('department'))
                    ->where('grade_id', $gradeId)
                    ->where('class', $request->input('class'))
                    ->whereNull('passed_at')
                    ->get();
            }

            $successes = [];

            $users->each(function (User $user) use ($request, $code, &$successes) {
                if (true === $this->service->apply($code, $user, $request->input('unity', false))) {
                    $successes[] = $user->getAttribute('username');
                }
            });
        }

        return $this->response->created([], $successes ?? []);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $code
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($code, $id)
    {
        $this->service->transform($id, $code);

        return $this->response->noContent();
    }

    /**
     * Delete the specific apply.
     *
     * @param string $code
     * @param int $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($code, $id)
    {
        try {
            $this->service->destroy($id);
        } catch (ApplyUncancelableException $e) {
            $this->response->error('applyUncancelable', 422);
        }

        return $this->response->noContent();
    }
}
