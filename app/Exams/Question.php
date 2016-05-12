<?php

namespace App\Exams;

use App\Categories\Category;
use App\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Entity
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['exam_id', 'difficulty_id', 'question_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uuid', 'content', 'multiple', 'difficulty_id', 'explanation', 'question_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the question difficulty.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function difficulty()
    {
        return $this->belongsTo(Category::class, 'difficulty_id');
    }

    /**
     * 取得題目的選項.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * 取得題目的答案.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function answers()
    {
        return $this->belongsToMany(Option::class, 'answers', null, 'option_id');
    }

    public function questions()
    {
        return $this->hasMany(self::class);
    }
}
