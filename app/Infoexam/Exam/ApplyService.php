<?php

namespace App\Infoexam\Exam;

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

    public function destroy($id)
    {
        $apply = Apply::with(['_list'])->find($id);

        if (null === $apply) {
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
        $this->list = Lists::where('code', '=', $code)->first();

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
        return null !== $this->list && $this->list->exists;
    }

    /**
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
        $this->list->applies()->save(new Apply([
            'user_id' => $request->user()->getAttribute('id'),
            'apply_type_id' => '24',    // todo: fix
        ]));
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
