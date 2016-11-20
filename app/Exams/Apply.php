<?php

namespace App\Exams;

use App\Accounts\User;
use App\Core\Entity;

class Apply extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'applies';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'listing_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'listing_id', 'type', 'paid_at', 'token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_at'];

    public function result()
    {
        return $this->hasOne(Result::class);
    }

    /**
     * 預約用戶.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 所屬測驗.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
