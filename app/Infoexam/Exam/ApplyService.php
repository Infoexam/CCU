<?php

namespace App\Infoexam\Exam;

use App\Infoexam\General\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApplyService
{
    /**
     * @var null|Lists
     */
    protected $list = null;

    /**
     * @var array
     */
    protected $applyId = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Check the request and perform apply.
     *
     * @param Request $request
     * @return $this
     */
    public function create(Request $request)
    {
        switch (false) {
            case $this->setList($request->input('code'))->listExists(): break;
            case $this->isListValid(): break;
            default:
                $this->store($request);
                break;
        }

        return $this;
    }

    /**
     * Delete apply
     *
     * @param Request $request
     * @param string $code
     * @param int $id
     * @return $this
     * @throws \Exception
     */
    public function destroy(Request $request, $code, $id)
    {
        $apply = Apply::with(['_list'])->find($id);

        if (! $request->user()->hasRole(['admin'])
            && $request->user()->getAttribute('id') !== $apply->getAttribute('user_id')) {
            $this->appendErrors('permission_deny');
        } else if (is_null($apply) || $apply->getRelation('_list')->getAttribute('code') !== $code) {
            $this->appendErrors('invalid_apply_id');
        } else if (Carbon::now()->diffInDays($apply->getRelation('_list')->getAttribute('began_at')) <= 1) {
            $this->appendErrors('too_late_to_cancel');
        } else {
            $apply->delete();
        }

        return $this;
    }

    /**
     * Get exam list and set the property.
     *
     * @param string $code
     * @return $this
     */
    protected function setList($code)
    {
        $this->list = Lists::where('code', $code)->first();

        if (! $this->listExists()) {
            $this->appendErrors('invalid_list_code');
        }

        return $this;
    }

    /**
     * Check exam list exists.
     *
     * @return bool
     */
    public function listExists()
    {
        return ! is_null($this->list) && $this->list->exists;
    }

    /**
     * Check list is valid.
     *
     * @return bool
     */
    protected function isListValid()
    {
        if (! $this->list->getAttribute('allow_apply')) {
            $this->appendErrors('can_not_apply_now');
        } else if ($this->list->getAttribute('std_apply_num') >= $this->list->getAttribute('std_maximum_num')) {
            $this->appendErrors('exceed_max_num');
        } else if (Carbon::now()->diffInDays($this->list->getAttribute('began_at')) <= 1) {
            $this->appendErrors('too_late_to_apply');
        } else {
            return true;
        }

        return false;
    }

    /**
     * Create and save the apply data.
     *
     * @param Request $request
     */
    protected function store(Request $request)
    {
        list($u, $a) = $request->user()->hasRole(['admin'])
            ? [$request->input('user_id'), Category::getCategories('exam.applied', 'admin', true)]
            : [$request->user()->getAttribute('id'), Category::getCategories('exam.applied', 'user', true)];

        $this->list->applies()->save(new Apply(['user_id' => $u, 'apply_type_id' => $a]));
    }
    
    /**
     * @return bool
     */
    public function success()
    {
        return 0 === count($this->errors);
    }

    /**
     * @param string $message
     * @return $this
     */
    protected function appendErrors($message)
    {
        $this->errors[] = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getApplyId()
    {
        return $this->applyId;
    }
}
