<?php

namespace App\Infoexam\Core;

use Auth;
use Carbon\Carbon;
use Eloquent;

class Entity extends Eloquent
{
    const MINUTES_PER_DAY = 1440;
    const MINUTES_PER_WEEK = 10080;
    const MINUTES_PER_MONTH = 40320;

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = [];

    /**
     * Get the table name of this model.
     *
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        $this->setAdditionalHiddenAttributes();

        return parent::toJson($options);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $this->setAdditionalHiddenAttributes();

        return parent::toArray();
    }

    /**
     * 未登入或非管理員帳號時增加額外需隱藏的欄位
     *
     * @return void
     */
    public function setAdditionalHiddenAttributes()
    {
        $hidden = session('role_and_permission_for_hidden_attributes');

        if (is_null($hidden)) {
            $hidden = Auth::guest() || ! Auth::user()->hasRole(['admin']);

            session()->flash('role_and_permission_for_hidden_attributes', $hidden);
        }

        if ($hidden) {
            $this->addHidden($this->notAdminHidden);
        }
    }

    /**
     * 將 created at 轉換成可閱讀文字
     *
     * @param string $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return $this->parsingDateAttribute($value);
    }

    /**
     * 將 updated at 轉換成可閱讀文字
     *
     * @param string $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return $this->parsingDateAttribute($value);
    }

    /**
     * Implement parsing.
     *
     * @param string $value
     * @return array
     */
    protected function parsingDateAttribute($value)
    {
        return [
            'time' => $value,
            'human' => Carbon::parse($value)->diffForHumans(Carbon::now())
        ];
    }
}
