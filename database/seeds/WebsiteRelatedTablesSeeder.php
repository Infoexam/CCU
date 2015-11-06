<?php

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
        factory(\App\Infoexam\Website\Announcement::class, random_int(15, 30))->create();

        factory(\App\Infoexam\Website\Faq::class, random_int(15, 30))->create();
    }
}
