<?php

namespace App\Console\Commands\Sync;

class ToCenter extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:to-center {student_id? : 學號}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將測驗資料同步到中心資料庫';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

    /**
     * 取得遠端資料
     *
     * @return array
     */
    protected function getRemoteData()
    {
        // TODO: Implement getRemoteData() method.
    }

    /**
     * @param \Illuminate\Support\Collection $data
     */
    protected function syncData($data)
    {
        // TODO: Implement syncData() method.
    }
}
