<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\General\Category;
use Cache;
use DB;

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
    protected $description = '更新系所代碼資料';

    /**
     * Local database departments.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $departments;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $departments = collect($this->getRemoteData());

        $this->departments = Category::getCategories('user.department');

        $this->analysis['total'] = $departments->count();

        $this->syncData($departments);

        Cache::forget('categoriesTable');

        $this->printResult();

        return $this->analysis;
    }

    /**
     * 取得系所代碼資料
     *
     * @return array
     */
    protected function getRemoteData()
    {
        return $this->trimData(DB::connection('elearn')->table('unit')->get());
    }


    /**
     * 同步資料
     *
     * @param \Illuminate\Support\Collection $departments
     */
    protected function syncData($departments)
    {
        foreach ($departments as $department) {
            $exists = $this->departments->search(function (Category $item) use ($department) {
                return $item->getAttribute('name') === $department->cd;
            });

            if (false !== $exists) {
                ++$this->analysis['notAffect'];
            } else {
                $category = Category::create([
                    'category' => 'user.department',
                    'name' => $department->cd,
                    'remark' => $department->name,
                ]);

                $category->exists ? ++$this->analysis['created'] : ++$this->analysis['fail'];
            }
        }

        $this->analysis['success'] = $this->analysis['created'] + $this->analysis['updated'];
    }
}
