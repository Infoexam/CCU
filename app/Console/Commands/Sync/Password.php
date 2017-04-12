<?php

namespace App\Console\Commands\Sync;

use DB;
use Infoexam\Eloquent\Models\User;

class Password extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:password {--id=* : 學號}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新本地資料庫學生密碼';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::connection('elearn')
            ->table('std_info')
            ->whereIn('std_no', $this->option('id'))
            ->get();

        $this->trim($users)->each(function ($info) {
            $user = User::where('username', $info['std_no'])->first();

            if (! is_null($user)) {
                $user->update([
                    'password' => bcrypt($info['user_pass']),
                    'version' => 1,
                ]);

                $this->info("Successfully update student {$info['std_no']} password.");
            }
        });
    }
}
