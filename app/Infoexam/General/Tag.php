<?php

namespace App\Infoexam\General;

use App\Infoexam\Core\Entity;

class Tag extends Entity
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'taggable_id', 'taggable_type'];

    /**
     * Get all of the owning taggable models.
     */
    public function taggable()
    {
        return $this->morphTo();
    }
}
