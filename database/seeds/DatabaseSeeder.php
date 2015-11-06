<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CategoriesTableSeeder::class);

        $this->call(EntrustSeeder::class);

        $this->call(WebsiteRelatedTablesSeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(ExamSetsTableSeeder::class);

        $this->call(PapersTableSeeder::class);

        $this->call(ExamListsTableSeeder::class);

        Model::reguard();
    }
}
