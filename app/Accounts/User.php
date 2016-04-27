<?php

namespace App\Accounts;

use App\Core\Entity;

class User extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['passed_at'];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
