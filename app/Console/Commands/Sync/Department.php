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
    protected $description = '更新本地資料庫的系所代碼資料';

    /**
     * Local database departments.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $departments;

    /**
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $departments = $this->getRemoteData();

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
     * @return \Illuminate\Support\Collection
     */
    protected function getRemoteData()
    {
        return collect($this->trimData(DB::connection('elearn')->table('unit')->get()));
    }


    /**
     * 同步資料
     *
     * @param \Illuminate\Support\Collection $departments
     * @return void
     */
    protected function syncData($departments)
    {
        foreach ($departments as $department) {
            $exists = $this->departments->search(function (Category $item) use ($department) {
                return $item->getAttribute('name') === $department->cd;
            });

            if (false !== $exists) {
                if ($this->departments[$exists]->getAttribute('remark') !== $department->name) {
                    $this->departments[$exists]->update(['remark' => $department->name]);

                    ++$this->analysis['updated'];
                } else {
                    ++$this->analysis['notAffect'];
                }
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
