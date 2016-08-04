<?php

namespace App\Core;

use Eloquent;

abstract class Entity extends Eloquent
{
    /**
     * Application version.
     */
    const VERSION = '0.0.2';

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * The attributes that should be replace sensitive characters.
     *
     * @var array
     */
    protected $urlSensitive = [];

    /**
     * Scope a query to order by RAND().
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRand($query)
    {
        return $query->getQuery()->orderByRaw('RAND()');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $model) {
            // Transform empty string to null
            foreach ($model->getAttributes() as $key => $value) {
                if (is_string($value) && empty($value)) {
                    $model->setAttribute($key, null);
                }
            }

            // Replace sensitive characters to '-'
            static $search = [' ', '/', '#', '　'];

            foreach ($model->urlSensitive as $key) {
                $model->setAttribute($key, str_replace($search, '-', $model->getAttribute($key)));
            }
        });
    }
}
