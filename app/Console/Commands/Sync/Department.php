<?php

namespace App\Console\Commands\Sync;

use DB;
use Illuminate\Support\Collection;
use Infoexam\Eloquent\Models\Category;

class Department extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新本地資料庫系所代碼';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fetch()
            ->pipe(function ($self) {
                $this->info('Sync data...');

                return $self;
            })
            ->each(function (array $dept) {
                DB::table('categories')
                    ->updateOrInsert([
                        'category' => 'user.department',
                        'name' => $dept['cd'],
                    ], [
                        'remark' => $dept['name'],
                    ]);
            });

        $this->postHandle();
    }

    /**
     * Get data to be handle.
     *
     * @return Collection
     */
    protected function fetch()
    {
        $this->info('Fetching data...');

        return $this->remote();
    }

    /**
     * Get synchronous data.
     *
     * @return Collection
     */
    protected function remote()
    {
        return $this->trim(DB::connection('elearn')->table('unit')->get());
    }

    /**
     * Get synchronized data.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function local()
    {
        return Category::getCategories('user.department');
    }
}
