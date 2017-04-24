<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropUnique('announcements_heading_unique');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->boolean('is_announcement')->default(false)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('is_announcement');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->unique('heading', 'announcements_heading_unique');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->softDeletes()->index();
        });
    }
}
