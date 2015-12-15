<?php

namespace App\Infoexam\User;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;

class Receipt extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['receipt_no', 'receipt_date', 'user_id', 'category_id'];

    /**
     * 取得該題庫的分類（學科、術科等）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 取得該收據的使用者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
