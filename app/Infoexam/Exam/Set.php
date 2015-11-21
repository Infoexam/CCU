<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use App\Infoexam\General\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Set extends Entity
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_sets';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['category_id'];

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['enable', 'category'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'category_id', 'enable'];

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
        'enable' => 'boolean',
    ];

    /**
     * 取得該題庫的分類（學科、術科等）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * 取得該題庫所有的題目
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_set_id');
    }

    /**
     * 取得該題庫所有題目的選項
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function options()
    {
        return $this->hasManyThrough(Option::class, Question::class, 'exam_set_id', 'exam_question_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (Set $set) {
            foreach ($set->load(['questions' => function (HasMany $relation) {
                $relation->getQuery()->getQuery()->select(['id', 'exam_set_id']);
            }])->getRelation('questions') as $question) {
                $question->delete();
            }
        });
    }
}
