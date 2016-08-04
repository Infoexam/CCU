<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;

abstract class Sync extends Command
{
    /**
     * Strip whitespace characters.
     *
     * @param \Illuminate\Support\Collection $data
     *
     * @return \Illuminate\Support\Collection
     */
    protected function trim($data)
    {
        return $data->map(function ($datum) {
            $datum = (array) $datum;

            foreach ($datum as $key => $value) {
                $datum[$key] = is_null($value) ? null : trim($value);
            }

            return $datum;
        });
    }
}
