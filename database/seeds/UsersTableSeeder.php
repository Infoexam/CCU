<?php

use App\Infoexam\User\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \App\Infoexam\User\Role::all()->pluck('id');

        factory(User::class, random_int(15, 30))->create()->each(function (User $user) use ($roles) {
            $user->certificate()->save(factory(\App\Infoexam\User\Certificate::class)->make());

            $r = $roles->random(random_int(1, $roles->count()));

            $user->roles()->sync(is_int($r) ? [$r] : $r->all());
        });

        factory(User::class, 'passed', random_int(15, 30))->create()->each(function (User $user) use ($roles) {
            $user->certificate()->save(factory(\App\Infoexam\User\Certificate::class)->make());

            $r = $roles->random(random_int(1, $roles->count()));

            $user->roles()->sync(is_int($r) ? [$r] : $r->all());
        });
    }
}
