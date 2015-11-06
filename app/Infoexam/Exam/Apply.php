<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apply extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_applies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'exam_list_id', 'apply_type_id', 'paid_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_at', 'deleted_at'];

    /**
     * 取得該報名的測驗結果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function result()
    {
        return $this->hasOne(Result::class, 'exam_apply_id');
    }

    /**
     * 取得該報名所屬的測驗
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function _list()
    {
        return $this->belongsTo(Lists::class, 'exam_list_id');
    }
}
