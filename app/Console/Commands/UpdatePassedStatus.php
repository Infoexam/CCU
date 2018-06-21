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

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();

        foreach($users as $user) {
            $cert = $user->certificates;
            
            $s = $cert->where('category_id', 16)->first();
            $t = $cert->where('category_id', 17)->first();

            $sScore = $s['score'];
            $tScore = $t['score'];

            $sPassed = $sScore >= 70 || $sScore == -999;
            $tPassed = $tScore >= 70 || $tScore == -999;

            if($sPassed && $tPassed) {
                $user->passed_at = max($s->updated_at, $t->updated_at);
                if($sScore == -999 || $tScore == -999) {
                    $user->passed_score = max($sScore, $tScore);
                }
                else {
                    $user->passed_score = ($sScore + $tScore) / 2;
                }
                $user->save();
            }
        }
    }
}
