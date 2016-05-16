<?php

namespace App\Exams;

use App\Categories\Category;
use App\Core\Entity;
use App\Media\UploadImagesTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Exam extends Entity implements HasMediaConversions
{
    use HasMediaTrait, SoftDeletes, UploadImagesTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['category_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'name', 'enable'];

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
        'enable' => 'boolean',
    ];

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
     * Register the conversions that should be performed.
     *
     * @return array
     */
    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 368])
            ->performOnCollections('*')
            ->nonQueued();
    }

    /**
     * Replace special char in name attribute.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setNameAttribute($value)
    {
        $search = [' ', '/', '#', '　'];

        $this->attributes['name'] = str_replace($search, '-', $value);

        return $this;
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
}
