<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\Listing;

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

        $this->now = Carbon::yesterday();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->listing()->each(function (Listing $listing) {
            $subject = Category::getCategories('exam.category', explode('-', $listing->getRelation('subject')->getAttribute('name'), 2)[1]);

            $questions = $listing->getAttribute('log')->getRelation('questions');

            $listing->getRelation('applies')->each(function (Apply $apply) use ($questions, $subject) {
                if (! is_null($result = $apply->getRelation('result'))) {
                    $certificate = $apply->getRelation('user')->getRelation('certificates')->where('category_id', $subject)->first();

                    if ($apply->getAttribute('type') !== 'U') {
                        try {
                            $certificate->decrement('free');
                        } catch (\PDOException $e) {
                            return;
                        }
                    }

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

                    $score = min(ceil(100 / $questions->count() * $correct), 100.0);

                    $result->update(['score' => $score]);

                    if ($score > $certificate->getAttribute('score')) {
                        $certificate->update(['score' => $score]);
                    }
                }
            });
        });
    }

    public function listing()
    {
        return Listing::with(['applies', 'applies.result', 'applies.user', 'applies.user.certificates', 'applyType', 'subject'])
            ->whereBetween('started_at', [$this->now->copy()->startOfDay(), $this->now->copy()->endOfDay()])
            ->get();
    }
}
