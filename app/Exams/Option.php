<?php

namespace App\Exams;

use App\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Entity
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'options';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
