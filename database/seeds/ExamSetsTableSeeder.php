<?php

use Illuminate\Database\Seeder;
use Infoexam\Eloquent\Models\Option;
use Infoexam\Eloquent\Models\Question;

class ExamSetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Set::class, mt_rand(5, 10))->create()->each(function (Set $set) {
            factory(Question::class, mt_rand(10, 15))->make()->each(function (Question $question) use ($set) {
                $set->questions()->save($question);

                if (mt_rand(0, 2)) {
                    $question->explanation()->save(factory(Explanation::class)->make());
                }

                factory(Option::class, mt_rand(3, 5))->make()->each(function ($option) use ($question) {
                    $question->options()->save($option);

                    if (mt_rand(0, 1)) {
                        $question->answers()->save($option);
                    }
                });
            });
        });
    }
}
