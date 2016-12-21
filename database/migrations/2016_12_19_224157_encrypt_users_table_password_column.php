<?php

use Illuminate\Database\Migrations\Migration;

class EncryptUsersTablePasswordColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->where('version', 1)->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['password' => encrypt($user->password)]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->where('version', 1)->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['password' => decrypt($user->password)]);
        });
    }
}
