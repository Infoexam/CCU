<?php

namespace App\Infoexam\Image;

use App\Infoexam\Core\Entity;
use Storage;

class Image extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['imageable_id', 'imageable_type'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uploaded_at', 'hash', 'extension', 'imageable_id', 'imageable_type'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['uploaded_at'];

    /**
     * Get all of the owning imageable models.
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (Image $image) {
            list($t, $h, $e) = [
                $image->getAttribute('uploaded_at')->timestamp,
                $image->getAttribute('hash'),
                $image->getAttribute('extension')
            ];

            Storage::disk('local')->delete([img_path($t, $h, $e, false, false), img_path($t, $h, $e, true, false)]);
        });
    }
}
