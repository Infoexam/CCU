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
            'user.gender' => ['male', 'female'],
            'user.grade' => ['freshman', 'sophomore', 'junior', 'seniors'],
            'exam.category' => ['acda', 'tech'],
            'exam.difficulty' => ['easy', 'middle', 'hard'],
            'user.department' => ['資工', '電機', '外文' ,'中文' ,'數學'],
        ];

        foreach ($static as $key => $value) {
            if (! Category::where('category', '=', $key)->exists()) {
                foreach ($value as $item) {
                    Category::create([
                        'category' => $key,
                        'name' => $item,
                    ]);
                }
            }
        }
    }
}
