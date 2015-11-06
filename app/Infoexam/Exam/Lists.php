<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use App\Infoexam\Paper\Paper;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lists extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'began_at', 'duration', 'room', 'paper_id', 'apply_type_id', 'subject_id',
        'std_maximum_num', 'std_apply_num', 'std_test_num', 'allow_apply', 'started_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['began_at', 'started_at', 'deleted_at'];

    /**
     * 取得該測驗的試卷
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * 取得該測驗的報名資料
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applies()
    {
        return $this->hasMany(Apply::class, 'exam_list_id');
    }

    /**
     * 取得該測驗的測驗結果
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function results()
    {
        return $this->hasManyThrough(Result::class, Apply::class, 'exam_list_id', 'exam_apply_id');
    }
}
