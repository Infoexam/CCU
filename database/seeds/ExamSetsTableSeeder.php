<?php

use App\Infoexam\Exam\Explanation;
use App\Infoexam\Exam\Option;
use App\Infoexam\Exam\Question;
use App\Infoexam\Exam\Set;
use Illuminate\Database\Seeder;

class ExamSetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Set::class, 5)->create()->each(function (Set $set) {
            factory(Question::class, 10)->make()->each(function (Question $question) use ($set) {
                $set->questions()->save($question);

                if (boolval(random_int(0, 2))) {
                    $question->explanation()->save(factory(Explanation::class)->make());
                }

                factory(Option::class, random_int(3, 5))->make()->each(function ($option) use ($question) {
                    $question->options()->save($option);

                    if (boolval(random_int(0, 1))) {
                        $question->answers()->save($option);
                    }
                });
            });
        });
    }
}
