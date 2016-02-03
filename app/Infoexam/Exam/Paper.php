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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'automatic' => 'boolean',
    ];

    /**
     * 如果 remark 為空字串，即轉換為 null
     *
     * @param  string  $value
     * @return void
     */
    public function setRemarkAttribute($value)
    {
        $this->attributes['remark'] = empty($value) ? null : $value;
    }

    /**
     * 取得使用該試卷的測驗
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function _lists()
    {
        return $this->hasMany(Lists::class);
    }

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
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool|int
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (empty($attributes['remark'])) {
            $attributes['remark'] = null;
        }

        return parent::update($attributes);
    }
}
