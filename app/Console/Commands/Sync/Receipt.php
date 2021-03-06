<?php

namespace App\Console\Commands\Sync;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Category;
use Infoexam\Eloquent\Models\User;
use Log;

class Receipt extends Sync
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:receipt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新本地資料庫繳費資料';

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
            ->each(function (array $receipt) {
                $user = User::with(['receipts', 'applies', 'applies.listing', 'applies.listing.subject'])
                    ->where('username', $this->studentId($receipt['payer_name']))
                    ->first();

                if (! is_null($user)) {
                    $model = $user->receipts()->save($this->receipt($receipt))->fresh(['category']);

                    DB::table('certificates')
                        ->where('user_id', $model->getAttribute('user_id'))
                        ->where('category_id', $model->getAttribute('category_id'))
                        ->increment('free');

                    $user->getRelation('applies')->each(function (Apply $apply) use ($model) {
                        if (str_contains($apply->getRelation('listing')->getRelation('subject')->getAttribute('name'), $model->getRelation('category')->getAttribute('name'))) {
                            $apply->update(['paid_at' => Carbon::now()]);
                        }
                    });
                } else {
                    Log::error('sync:receipt', [
                        'type' => 'user-not-found',
                        'receipt_no' => $receipt['receipt_no'],
                        'student_id' => $this->studentId($receipt['payer_name']),
                    ]);
                }
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
        $data = DB::connection('receipt')
            ->table('c0etreceipt_mt')
            ->where('receipt_date', (Carbon::yesterday()->year - 1911).(Carbon::yesterday()->format('md')))
            ->leftJoin('c0etreceipt_acc_dt', 'c0etreceipt_mt.receipt_no', '=', 'c0etreceipt_acc_dt.receipt_no')
            ->where('note', 'like', '資訊能力%')
            ->where('acc5_cd', '420298-3000')
            ->get();

        return $this->trim($data);
    }

    /**
     * Get synchronized data.
     *
     * @return null
     */
    protected function local()
    {
        return null;
    }

    /**
     * Get student id number.
     *
     * @param string $name
     *
     * @return string
     */
    protected function studentId($name)
    {
        return mb_substr($name, mb_strpos($name, ':') + 1, 9);
    }

    /**
     * Get receipt info.
     *
     * @param array $receipt
     *
     * @return \Infoexam\Eloquent\Models\Receipt
     */
    protected function receipt(array $receipt)
    {
        return new \Infoexam\Eloquent\Models\Receipt([
            'receipt_no' => $receipt['receipt_no'],
            'receipt_date' => $receipt['receipt_date'],
            'category_id' => $this->categoryId($receipt['note']),
        ]);
    }

    /**
     * Get related category id.
     *
     * @param string $note
     *
     * @return int
     */
    protected function categoryId($note)
    {
        switch (true) {
            case str_contains($note, '學科'):
                return Category::getCategories('exam.category', 'theory', true);
            case str_contains($note, '術科'):
                return Category::getCategories('exam.category', 'tech', true);
            default:
                return Category::getCategories('error', 'general', true);
        }
    }
}
