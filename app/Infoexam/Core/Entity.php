<?php

namespace App\Infoexam\Core;

use Auth;
use Eloquent;

class Entity extends Eloquent
{
    /**
     * 未登入或非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $guestOrNotAdminHidden = [];

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
        if (Auth::guest() || ! Auth::user()->hasRole(['admin'])) {
            $this->addHidden($this->guestOrNotAdminHidden);
        }
    }
}
