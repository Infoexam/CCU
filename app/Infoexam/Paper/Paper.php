<?php

namespace App\Infoexam\Paper;

use App\Infoexam\Core\Entity;
use App\Infoexam\Exam\Question;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paper extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'papers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'remark', 'automatic'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 取得該試卷的題目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'paper_exam_question', 'paper_id', 'exam_question_id');
    }
}
