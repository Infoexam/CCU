<?php

namespace App\Exams;

use App\Core\Entity;

class Listing extends Entity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'listings';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['began_at', 'ended_at', 'started_at'];

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
