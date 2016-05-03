<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(WebsiteRelatedTablesSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ExamSetsTableSeeder::class);
        $this->call(PapersTableSeeder::class);
        $this->call(ExamListsTableSeeder::class);
    }
}
