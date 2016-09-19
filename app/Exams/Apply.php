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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_at'];

    public function result()
    {
        return $this->hasOne(Result::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
