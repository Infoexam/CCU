<?php

namespace App\Accounts;

use App\Core\Entity;

class Certificate extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certificates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['score'];
}
