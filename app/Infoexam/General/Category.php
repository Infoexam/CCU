<?php

namespace App\Infoexam\General;

use App\Infoexam\Core\Entity;

class Category extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['id', 'category', 'remark'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category', 'name', 'remark'];
}
