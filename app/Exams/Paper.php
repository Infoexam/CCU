<?php

namespace App\Exams;

use App\Core\Entity;
use DB;

class Paper extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'papers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'remark', 'automatic'];

    /**
     * The attributes that should be replace sensitive characters.
     *
     * @var array
     */
    protected $urlSensitive = ['name'];

    /**
     * 取得試卷題目.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    /**
     * 取得使用本試卷的測驗.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $paper) {
            // Delete questions of the paper.
            DB::table('paper_question')
                ->where('paper_id', $paper->getKey())
                ->delete();
        });
    }
}
