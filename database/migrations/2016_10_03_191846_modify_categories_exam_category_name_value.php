<?php

use Illuminate\Database\Migrations\Migration;

class ModifyCategoriesExamCategoryNameValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('categories')
            ->where('category', 'exam.category')
            ->where('name', 'technology')
            ->update([
                'name' => 'tech',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')
            ->where('category', 'exam.category')
            ->where('name', 'tech')
            ->update([
                'name' => 'technology',
            ]);
    }
}
