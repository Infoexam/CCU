<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexForOptimization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->index('category_id');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->index('exam_apply_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_name_index');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex('certificates_category_id_index');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex('exam_results_exam_apply_id_index');
        });
    }
}
