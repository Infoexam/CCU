<?php

namespace App\Exams;

use App\Core\Entity;

class Result extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'results';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['signed_in_at', 'submitted_at'];
}