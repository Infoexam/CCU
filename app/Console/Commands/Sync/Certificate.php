<?php

namespace App\Console\Commands\Sync;

use App\Accounts\User;
use App\Categories\Category;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Certificate extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新中心資料庫測驗資料';

    /**
     * Mapping categories id to name.
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->mapping();

        $this->fetch()
            ->pipe(function ($self) {
                $this->info('Sync data...');

                return $self;
            })
            ->each(function (User $user) {
                DB::connection('elearn')
                    ->table('certificates')
                    ->updateOrInsert([
                        'username' => $user->getAttribute('username'),
                    ], [
                        'scores' => $this->certificates($user->getRelation('certificates')),
                        'test_count' => $user->getAttribute('test_count'),
                        'passed_score' => $user->getAttribute('passed_score'),
                        'passed_at' => $user->getAttribute('passed_at'),
                    ]);
            });

        $this->postHandle();
    }

    /**
     * Create departments and grades mapping data.
     *
     * @return void
     */
    protected function mapping()
    {
        $this->categories = Category::getCategories('exam.category')
            ->pluck('name', 'id')
            ->toArray();
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function remote()
    {
        return User::with(['certificates'])
            ->whereHas('certificates', function (Builder $query) {
                $query->where('updated_at', '>=', Carbon::yesterday()->startOfDay());
            })
            ->where('role', 'under')
            ->get();
    }

    /**
     * Get synchronized data.
     *
     * @return array
     */
    protected function local()
    {
        return DB::connection('elearn')->table('certificates')->get();
    }

    /**
     * Transform certificates to json format.
     *
     * @param \Illuminate\Database\Eloquent\Collection $certificates
     *
     * @return string
     */
    protected function certificates($certificates)
    {
        return $certificates->map(function (\App\Accounts\Certificate $certificate) {
            return [
                $this->categories[$certificate->getAttribute('category_id')] => [
                    'score' => $certificate->getAttribute('score'),
                    'free' => $certificate->getAttribute('free'),
                ],
            ];
        })
            ->flatten(1)
            ->toJson();
    }
}
