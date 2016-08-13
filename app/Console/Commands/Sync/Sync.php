<?php

namespace App\Console\Commands\Sync;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

abstract class Sync extends Command
{
    /**
     * Log sync.
     *
     * @return void
     */
    protected function postHandle()
    {
        Log::info('sync-success', [
            'command' => $this->getName(),
            'sync_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

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
