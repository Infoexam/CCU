<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class JudgeSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'judgesession';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批改指定場次測驗(listings.code)';

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
                    $corrects = [];

                    $log = array_map(function ($val) {
                        return is_array($val) ? array_map('intval', $val) : intval($val);
                    }, $result->getAttribute('log')['answer'] ?? []);

                    foreach ($questions as $question) {
                        $uuid = $question->getAttribute('uuid');

                        if (! isset($log[$uuid])) {
                            continue;
                        }

                        $options = $question->getRelation('options')->where('answer', true)->pluck('id')->toArray();

                        $student = is_array($log[$uuid]) ? $log[$uuid] : [$log[$uuid]];

                        if (! $question->getAttribute('multiple')) {
                            if (array_diff($options, $student) === array_diff($student, $options)) {
                                $correct += 1.0;
                                $corrects[] = $uuid;
                            }
                        } else {
                            $temp = max(1 - (count(array_diff($options, $student)) + count(array_diff($student, $options))) * 2 / $question->getRelation('options')->count(), 0);

                            $correct += $temp;

                            if (1 === $temp) {
                                $corrects[] = $uuid;
                            }
                        }
                    }

                    $score = min(ceil(100 / $questions->count() * $correct), 100.0);

                    $newLog = $result->getAttribute('log');
                    $newLog['result'] = $corrects;

                    $result->update(['score' => $score, 'log' => $newLog]);

                    if (is_null($certificate->getAttribute('score')) || (0 > $certificate->getAttribute('score') && $score > $certificate->getAttribute('score'))) {
                        $certificate->update(['score' => $score]);
                    }
                }
            });
        });
    }

    public function listing()
    {
        $judgeSession = $this->ask('Session code: ');
        $this->now = Carbon::createFromFormat('Y-m-d', $judgeDay);
        $categories = Category::getCategories('exam.subject')
            ->filter(function (Category $category) {
                return ends_with($category->getAttribute('name'), 'theory');
            })
            ->pluck('id')
            ->toArray();

        return Listing::with(['applies', 'applies.result', 'applies.user', 'applies.user.certificates', 'applyType', 'subject'])
            ->whereBetween('started_at', [$this->now->copy()->startOfDay(), $this->now->copy()->endOfDay()])
            ->whereIn('subject_id', $categories)
            ->where('code', $judgeSession)
            ->get();
    }
}
