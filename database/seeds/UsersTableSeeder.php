<?php

use App\Infoexam\User\Certificate;
use App\Infoexam\User\Role;
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
        $roles = Role::all()->pluck('id');

        if (app()->environment(['local', 'testing'])) {
            if (! User::where('username', '=', 'test')->exists()) {
                factory(User::class)->create([
                    'username' => 'test',
                    'password' => bcrypt('test'),
                ])->roles()->sync($roles->all());
            }
        }

        factory(User::class, mt_rand(15, 30))->create()->each(function (User $user) use ($roles) {
            $user->certificates()->save(factory(Certificate::class)->make());

            $r = $roles->random(mt_rand(1, $roles->count()));

            $user->roles()->sync(is_int($r) ? [$r] : $r->all());
        });

        factory(User::class, 'passed', mt_rand(15, 30))->create()->each(function (User $user) use ($roles) {
            $user->certificates()->save(factory(Certificate::class)->make());

            $r = $roles->random(mt_rand(1, $roles->count()));

            $user->roles()->sync(is_int($r) ? [$r] : $r->all());
        });
    }
}
