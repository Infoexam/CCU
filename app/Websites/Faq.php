<?php

namespace App\Websites;

use App\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Entity
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faqs';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
