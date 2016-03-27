<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropPrimary(['uploaded_at', 'hash']);
            $table->dropColumn('uploaded_at');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->unsignedBigInteger('hash')->primary()->change();
            $table->string('extension', 12)->change();

            $table->timestamp('uploaded_at')->nullable()->after('extension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropPrimary(['hash']);
        });

        Schema::table('images', function (Blueprint $table) {
            $table->unsignedInteger('hash')->change();
            $table->string('extension', 16)->change();

            $table->primary(['uploaded_at', 'hash']);
        });
    }
}
