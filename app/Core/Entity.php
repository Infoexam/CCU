<?php

namespace App\Core;

use Eloquent;

abstract class Entity extends Eloquent
{
    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;
}
