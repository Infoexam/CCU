<?php

namespace App\Exams;

use App\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Entity
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function answers()
    {
        return $this->belongsToMany(Option::class, 'answers', null, 'option_id');
    }
}
