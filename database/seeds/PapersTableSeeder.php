<?php

use App\Infoexam\Exam\Paper;
use Illuminate\Database\Seeder;

class PapersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = \App\Infoexam\Exam\Question::all()->pluck('id');

        factory(Paper::class, mt_rand(10, 15))->create()->each(function (Paper $paper) use ($questions) {
            if ($questions->count()) {
                $q = $questions->random(mt_rand(1, $questions->count()));

                $paper->questions()->sync(is_int($q) ? [$q] : $q->all());
            }
        });
    }
}
