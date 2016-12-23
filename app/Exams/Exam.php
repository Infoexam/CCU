<?php

namespace App\Exams;

use App\Categories\Category;
use Infoexam\Media\Media;
use Venturecraft\Revisionable\RevisionableTrait;

class Exam extends Media
{
    use RevisionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['category_id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'name', 'enable'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enable' => 'boolean',
    ];

    /**
     * The attributes that should be replace sensitive characters.
     *
     * @var array
     */
    protected $urlSensitive = ['name'];

    /**
     * Check if we should store creations in our revision history.
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * 取得題庫類型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 取得題庫的題目.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Convert boolean equivalent to bool.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setEnableAttribute($value)
    {
        $this->attributes['enable'] = in_array($value, ['1', 1, true, 'true'], true);

        return $this;
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            // Transform empty string to null
            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value) && empty($value)) {
                    $model->setAttribute($key, null);
                }
            }

            // Replace sensitive characters to '-'
            static $search = [' ', '\\', '/', '#', '　'];

            foreach ($model->urlSensitive as $key) {
                $model->setAttribute($key, str_replace($search, '-', $model->getAttribute($key)));
            }
        });

        static::deleting(function (self $exam) {
            // Delete questions of the exam.
            $exam->load(['questions'])
                ->getRelation('questions')
                ->each(function (Question $question) {
                    $question->delete();
                });
        });
    }
}
