<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;

abstract class Sync extends Command
{
    /**
     * 同步數據結果
     *
     * @var array
     */
    protected $analysis = [
        'total' => 0,
        'success' => 0, 'fail' => 0, 'notAffect' => 0,
        'create' => 0, 'created' => 0,
        'update' => 0, 'updated' => 0,
    ];

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 跳脫空白字元
     *
     * @param array $data
     * @return array
     */
    protected function trimData($data)
    {
        foreach ($data as $datum) {
            foreach ($datum as $key => $value) {
                $datum->$key = trim($value);
            }
        }

        return $data;
    }

    /**
     * 同步資料
     *
     * @param $data
     */
    abstract protected function syncData($data);
}
