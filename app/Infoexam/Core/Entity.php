<?php

namespace App\Infoexam\Core;

use Auth;
use Cache;
use Carbon\Carbon;
use Eloquent;

class Entity extends Eloquent
{
    const MINUTES_QUARTER_DAY = 360;
    const MINUTES_HALF_DAY = 720;
    const MINUTES_PER_DAY = 1440;
    const MINUTES_PER_WEEK = 10080;
    const MINUTES_PER_MONTH = 40320;

    /**
     * Infoexam Version
     */
    const VERSION = '0.0.4';

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
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $hidden = true;

        if (! Auth::guest()) {
            $key = Auth::user()->getAttribute('id') . ':admin';

            $hidden = ! Cache::tags('user_role')->remember($key, 5, function () {
                return Auth::user()->hasRole(['admin']);
            });
        }

        if ($hidden) {
            $this->addHidden($this->notAdminHidden);
        }

        return parent::toArray();
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
