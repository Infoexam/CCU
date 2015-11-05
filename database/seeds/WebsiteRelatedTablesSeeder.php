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
        factory(\App\Infoexam\Website\Announcement::class, 20)->create();

        factory(\App\Infoexam\Website\Faq::class, 20)->create();
    }
}
