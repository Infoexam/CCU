<?php

use App\Infoexam\General\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $static = [
            'error' => ['general', 'not-found'],
            'user.gender' => ['male', 'female'],
            'user.grade' => ['freshman', 'sophomore', 'junior', 'senior', 'deferral', 'admin'],
            'user.department' => ['4104', '4204', '4304' ,'4404' ,'4504'],
            'exam.category' => ['theory', 'technology'],
            'exam.difficulty' => ['easy', 'middle', 'hard'],
            'exam.subject' => ['app-theory', 'app-tech', 'soft-theory', 'soft-tech'],
            'exam.apply' => ['unlimited', 'senior-only', 'unity', 'makeup'],
            'exam.applied' => ['admin', 'user'],
        ];

        foreach ($static as $category => $items) {
            foreach ($items as $item) {
                Category::firstOrCreate([
                    'category' => $category,
                    'name' => $item
                ]);
            }
        }
    }
}
