<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;
use App\Infoexam\Image\Image;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_questions';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['difficulty_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['exam_set_id', 'content', 'difficulty_id', 'multiple'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['images'];

    /**
     * 取得該題目的選項
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class, 'exam_question_id');
    }

    /**
     * 取得該題目的圖片
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * 取得該題目的難度
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function difficulty()
    {
        return $this->belongsTo(Category::class, 'difficulty_id');
    }

    /**
     * 取得該題目的解釋
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function explanation()
    {
        return $this->hasOne(Explanation::class, 'exam_question_id');
    }

    /**
     * 取得該題目的答案
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function answers()
    {
        return $this->belongsToMany(Option::class, 'exam_answers', 'exam_question_id', 'exam_option_id');
    }

    /**
     * 取得該題目所屬的題庫
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function set()
    {
        return $this->belongsTo(Set::class, 'exam_set_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (Question $question) {
            $question->load(['explanation' => function (HasOne $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'exam_question_id']);
            }]);

            // 刪除解析
            if (null !== ($explanation = $question->getRelation('explanation'))) {
                $explanation->delete();
            }

            // 刪除選項
            Option::where('exam_question_id', '=', $question->getAttribute('id'))->delete();
        });
    }
}
