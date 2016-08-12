<?php

use App\Accounts\Certificate;
use App\Accounts\Receipt;
use App\Accounts\User;
use App\Categories\Category;
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
        if (app()->environment(['local', 'testing']) && ! User::where('username', 'test')->exists()) {
            factory(User::class)->create([
                'username' => 'test',
                'password' => bcrypt('test'),
                'role'     => 'admin',
            ]);
        }

        $categories = Category::getCategories('exam.category')->pluck('id')->toArray();

        factory(User::class, mt_rand(20, 25))
            ->create()
            ->merge(factory(User::class, 'passed', mt_rand(20, 25))->create())
            ->each(function (User $user) use ($categories) {
                foreach ($categories as $category) {
                    $user->certificates()
                        ->save(factory(Certificate::class)->make(['category_id' => $category]));
                }

                $user->receipts()->saveMany(factory(Receipt::class)->make());
            });
    }
}
