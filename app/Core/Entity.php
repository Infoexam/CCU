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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value) && empty($value)) {
                    $model->setAttribute($key, null);
                }
            }
        });
    }
}
