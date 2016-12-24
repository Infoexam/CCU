<?php

use Infoexam\Eloquent\Models\Paper;
use Infoexam\Eloquent\Models\Question;
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
        $questions = Question::all()->pluck('id');

        factory(Paper::class, mt_rand(10, 15))->create()->each(function (Paper $paper) use ($questions) {
            if ($questions->count()) {
                $q = $questions->random(mt_rand(1, $questions->count()));

                $paper->questions()->sync(is_int($q) ? [$q] : $q->all());
            }
        });
    }
}
