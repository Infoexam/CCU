<?php

namespace App\Exams;

use App\Categories\Category;
use App\Core\Entity;
use Carbon\Carbon;
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'multiple' => 'boolean',
    ];

    /**
     * The attributes that should be replace sensitive characters.
     *
     * @var array
     */
    protected $urlSensitive = ['uuid'];

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
     * 題組關聯.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(self::class);
    }

    /**
     * 題目所屬題庫.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * 題目所屬試卷.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function papers()
    {
        return $this->belongsToMany(Paper::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $question) {
            // Delete the question's sub questions.
            foreach ($question->load(['questions'])->getRelation('questions') as $subQuestion) {
                $question->forceDeleting ? $subQuestion->forceDelete() : $subQuestion->delete();
            }

            if (! $question->forceDeleting) {
                $question->update(['uuid' => $question->getAttribute('uuid').'-'.Carbon::now()->timestamp]);
            } else {
                // Delete the question's options if it is force deleting.
                $question->load(['options'])->getRelation('options')->each(function (Option $option) {
                    $option->forceDelete();
                });
            }
        });
    }
}
