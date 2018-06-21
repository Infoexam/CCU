<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Infoexam\Eloquent\Models\Certificate;
use Infoexam\Eloquent\Models\User;


class UpdatePassedStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update passed status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $users = User::all();

        foreach($user as $users) {
            $cert = Certificate::where('user_id', $user->id);
            $s = $cert->where('category_id', 16)->get();
            $t = $cert->where('category_id', 17)->get();
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
