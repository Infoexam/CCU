<?php

namespace App\Infoexam\User;

use App\Infoexam\Core\Entity;

class Certificate extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certificates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'category_id', 'score'];

    /**
     * 取得該成績資訊所屬的使用者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
