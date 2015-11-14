<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paper extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_papers';

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
        return $this->belongsToMany(Question::class, 'exam_paper_exam_question', 'exam_paper_id', 'exam_question_id')
            ->withPivot(['id']);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        if (empty($attributes['remark'])) {
            $attributes['remark'] = null;
        }

        return parent::create($attributes);
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @return bool|int
     */
    public function update(array $attributes = [])
    {
        if (empty($attributes['remark'])) {
            $attributes['remark'] = null;
        }

        return parent::update($attributes);
    }
}
