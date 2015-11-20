<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Explanation extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_explanations';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['exam_question_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['exam_question_id', 'content'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 取得該解釋所屬的題目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'exam_question_id');
    }
}
