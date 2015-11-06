<?php

use App\Infoexam\Exam\Apply;
use App\Infoexam\Exam\Lists;
use App\Infoexam\Exam\Result;
use Illuminate\Database\Seeder;

class ExamListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Lists::class, random_int(5, 10))->create()->each(function (Lists $list) {
            factory(Apply::class, random_int(10, 15))->make()->each(function (Apply $apply) use ($list) {
                $list->applies()->save($apply);

                if (random_int(0, 1)) {
                    $apply->result()->save(factory(Result::class)->make());
                }
            });
        });
    }
}
