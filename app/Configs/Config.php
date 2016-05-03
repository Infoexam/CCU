<?php

namespace App\Configs;

use App\Core\Entity;

class Config extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configs';

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
}