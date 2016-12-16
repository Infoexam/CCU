<?php

namespace App\Exams;

use App\Categories\Category;
use App\Core\Entity;
use Carbon\Carbon;

class Listing extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'listings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['began_at', 'duration', 'started_at', 'room', 'applicable', 'apply_type_id', 'subject_id', 'maximum_num', 'log'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['began_at', 'ended_at', 'started_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'applicable' => 'boolean',
    ];

    /**
     * 已報名使用者.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    /**
     * 報名方式.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applyType()
    {
        return $this->belongsTo(Category::class, 'apply_type_id');
    }

    /**
     * 測驗類型.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Category::class, 'subject_id');
    }

    /**
     * 測驗試卷.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function setBeganAtAttribute($value)
    {
        $this->attributes['began_at'] = Carbon::parse($value);
    }

    public function getLogAttribute($value)
    {
        return unserialize($value);
    }

    public function setLogAttribute($value)
    {
        $this->attributes['log'] = serialize($value);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $listing) {
            $listing->setAttribute('code', $listing->getAttribute('began_at')->format('YmdHi').$listing->getAttribute('room'));

            if (! is_null($listing->getAttribute('started_at'))) {
                $listing->setAttribute('ended_at', $listing->getAttribute('started_at')->copy()->addMinutes($listing->getAttribute('duration')));
            } else {
                $listing->setAttribute('ended_at', $listing->getAttribute('began_at')->copy()->addMinutes($listing->getAttribute('duration')));
            }
        });
    }
}
