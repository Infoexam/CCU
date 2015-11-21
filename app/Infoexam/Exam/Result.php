<?php

namespace App\Infoexam\Exam;

use App\Infoexam\Core\Entity;

class Result extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'exam_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['exam_apply_id', 'score', 'log', 'allow_re_sign_in', 'signed_in_at', 'submitted_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['signed_in_at', 'submitted_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'allow_re_sign_in' => 'boolean',
    ];

    /**
     * 取得該測驗結果的報名資料
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apply()
    {
        return $this->belongsTo(Apply::class, 'exam_apply_id');
    }
}
