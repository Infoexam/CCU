<?php

namespace App\Exams;

use App\Core\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Entity
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exams';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
