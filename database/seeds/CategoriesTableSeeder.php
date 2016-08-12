<?php

use App\Categories\Category;
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
            'user.grade' => ['freshman', 'sophomore', 'junior', 'senior', 'deferral', 'admin'],
            'user.department' => ['未知系所' => '0000', '管理員' => '9999', '測試' => '123'],
            'exam.category' => ['theory', 'technology'],
            'exam.difficulty' => ['easy', 'middle', 'hard'],
            'exam.subject' => ['app-theory', 'app-tech', 'soft-theory', 'soft-tech'],
            'exam.apply' => ['unlimited', 'senior-only', 'unity', 'makeup'],
            'exam.applied' => ['admin', 'user'],
        ];

        foreach ($static as $category => $items) {
            foreach ($items as $key => $item) {
                Category::updateOrCreate([
                    'category' => $category,
                    'name' => $item,
                ], [
                    'remark' => is_int($key) ? null : $key,
                ]);
            }
        }
    }
}
