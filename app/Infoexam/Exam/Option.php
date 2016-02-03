<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;
use App\Infoexam\Image\Image;
use App\Infoexam\Image\UploadTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Entity
{
    use SoftDeletes;
    use UploadTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_options';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['exam_question_id'];

    /**
     * 非管理員帳號需隱藏的欄位
     *
     * @var array
     */
    protected $notAdminHidden = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['exam_question_id', 'content'];

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
     * 取得該選項的圖片
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * 取得該選項所屬的題目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'exam_question_id');
    }
}
