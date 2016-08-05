<?php

namespace App\Exams;

use App\Core\Entity;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paper extends Entity
{
    use SoftDeletes;

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
    protected $fillable = ['name', 'remark'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
            if (! $paper->forceDeleting) {
                $paper->update(['name' => $paper->getAttribute('name').'-'.Carbon::now()->timestamp]);
            } else {
                DB::table('paper_question')->where('paper_id', $paper->getKey())->delete();
            }
        });
    }
}
