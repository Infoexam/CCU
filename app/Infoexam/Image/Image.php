<?php

namespace App\Infoexam\Image;

use App\Infoexam\Core\Entity;
use Carbon\Carbon;
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
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'hash';

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
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['uploaded_at', 'hash', 'extension'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['link'];

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
     * 取得圖片連結
     *
     * @return array
     */
    public function getLinkAttribute()
    {
        list($t, $h, $e) = [
            Carbon::parse($this->attributes['uploaded_at'])->timestamp,
            $this->attributes['hash'],
            $this->attributes['extension'],
        ];

        return [
            'original' => img_src($t, $h, $e),
            'thumbnail' => img_src($t, $h, $e, true),
        ];
    }

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
