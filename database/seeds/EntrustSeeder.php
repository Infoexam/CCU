<?php

use App\Infoexam\User\Permission;
use App\Infoexam\User\Role;
use Illuminate\Database\Seeder;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'developer', 'invigilator', 'undergraduate', 'graduate'];

        $permissions = ['sign-in', 'create-announcement', 'create-test', 'create-paper'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $permissions = Permission::all()->pluck('id');

        foreach ($roles as $role) {
            $r = Role::firstOrCreate(['name' => $role]);

            if ($permissions->count()) {
                $p = $permissions->random(random_int(1, $permissions->count()));

                $r->perms()->sync(is_int($p) ? [$p] : $p->all());
            }
        }
    }
}
