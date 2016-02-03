<?php

use App\Infoexam\Website\Announcement;
use App\Infoexam\Website\Faq;
use Illuminate\Database\Seeder;

class WebsiteRelatedTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Announcement::class, mt_rand(15, 30))->create();

        factory(Faq::class, mt_rand(15, 30))->create();
    }
}
