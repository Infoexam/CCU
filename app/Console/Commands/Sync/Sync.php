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
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $this->printResult();

        return $this->analysis;
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
                $datum->$key = is_null($value) ? null : trim($value);
            }
        }

        return $data;
    }

    /**
     * 輸出結果
     *
     * @return void
     */
    protected function printResult()
    {
        $this->getOutput()->writeln("<info>Total: {$this->analysis['total']}</info>");
        $this->getOutput()->writeln(
            "<info>Success: {$this->analysis['success']}</info> | " .
            "<comment>Not Affect: {$this->analysis['notAffect']}</comment> | " .
            "<error>Fail: {$this->analysis['fail']}</error>"
        );
        $this->getOutput()->writeln("<comment>Create: {$this->analysis['created']}/{$this->analysis['create']}</comment>");
        $this->getOutput()->writeln("<comment>Update: {$this->analysis['updated']}/{$this->analysis['update']}</comment>");
    }

    abstract protected function getSourceData();

    abstract protected function syncDestinationData($data);
}
