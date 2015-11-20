<?php

namespace App\Infoexam\User;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;

class Certificate extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certificates';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'category_id'];

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['id', 'free'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'category_id', 'score', 'free'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * 取得該成績資訊所屬的使用者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 取得該成績的類別（學科、術科）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
