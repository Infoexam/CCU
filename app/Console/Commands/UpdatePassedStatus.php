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
            $applies = $user->applies;
            
            $maxScore = 0;
            foreach($applies as $apply) {
                $subject = $apply->listing->subject_id;
                if($subject == 21 || $subject == 23) { // 跳過學科
                    continue;
                }

                $result = $apply->result;
                if($result != null) {
                    $score = $apply->result->score;
                    $maxScore = max($maxScore, $score);
                }
            }
            //echo $maxScore;
            
            $t = $cert->where('category_id', 16)->first(); //學科
            $s = $cert->where('category_id', 17)->first(); //術科

            $tScore = $t['score'];
            $sScore = $s['score'];

            if($maxScore > $sScore && $sScore != -999) {
                $s['score'] = $maxScore;
                $s->save();
                $sScore = $s['score'];
            }

            $tPassed = $tScore >= 70 || $tScore == -999;
            $sPassed = $sScore >= 70 || $sScore == -999;

            if($tPassed && $sPassed) {
                $user->passed_at = max($t->updated_at, $s->updated_at);
                if($tScore == -999 || $sScore == -999) {
                    $user->passed_score = max($tScore, $sScore);
                }
                else {
                    $user->passed_score = ($tScore + $sScore) / 2;
                }
                $user->save();
            }
        }
    }
}
