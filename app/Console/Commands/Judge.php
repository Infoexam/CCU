<?php

namespace App\Console\Commands;

use App\Exams\Apply;
use App\Exams\Listing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Judge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'judge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批改測驗';

    /**
     * @var Carbon
     */
    protected $now;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

//        $this->now = Carbon::yesterday();
        $this->now = Carbon::now();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->listing()->each(function (Listing $listing) {
            $questions = $listing->getAttribute('log')->getRelation('questions');

            $listing->getRelation('applies')->each(function (Apply $apply) use ($questions) {
                if (! is_null($result = $apply->getRelation('result'))) {
                    $correct = 0.0;

                    $log = array_map('intval', $result->getAttribute('log') ?? []);

                    foreach ($questions as $question) {
                        $uuid = $question->getAttribute('uuid');

                        if (! isset($log[$uuid])) {
                            continue;
                        }

                        $options = $question->getRelation('options')->where('answer', true)->pluck('id')->toArray();

                        $student = is_array($log[$uuid]) ? $log[$uuid] : [$log[$uuid]];

                        $intersect = array_intersect($options, $student);

                        if (! $question->getAttribute('multiple')) {
                            if (! empty($intersect)) {
                                $correct += 1.0;
                            }
                        } else {
                            $correct += max(count($intersect) - count(array_intersect($student, $options)), 0) / $question->getRelation('options')->count();
                        }
                    }

                    $score = ceil(100 / $questions->count() * $correct);

                    $result->update(['score' => $score]);

                    // @todo money
                }
            });
        });
    }

    public function listing()
    {
        return Listing::with(['applies', 'applies.result', 'applies.user', 'applyType', 'subject'])
            ->whereBetween('started_at', [$this->now->copy()->startOfDay(), $this->now->copy()->endOfDay()])
            ->get();
    }
}
