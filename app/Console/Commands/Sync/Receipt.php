<?php

namespace App\Console\Commands\Sync;

use App\Infoexam\General\Category;
use App\Infoexam\User\Receipt as ReceiptEntity;
use App\Infoexam\User\User;
use Carbon\Carbon;
use DB;

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
    protected $description = '更新本地資料庫的收據資料';

    /**
     * Execute the console command.
     *
     * @return array
     */
    public function handle()
    {
        $receipts = $this->getSourceData();

        $this->analysis['total'] = $receipts->count();

        $this->syncDestinationData($receipts);

        return parent::handle();
    }

    /**
     * 取得收據資料.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getSourceData()
    {
        $receipts = DB::connection('receipt')->table('c0etreceipt_mt')
            ->where('receipt_date', (Carbon::yesterday()->year - 1911).(Carbon::yesterday()->format('md')))
            ->leftJoin('c0etreceipt_acc_dt', 'c0etreceipt_mt.receipt_no', '=', 'c0etreceipt_acc_dt.receipt_no')
            ->where('acc5_cd', '422Y-300')
            ->get();

        return collect($this->trimData($receipts));
    }

    /**
     * 同步資料.
     *
     * @param \Illuminate\Support\Collection $receipts
     * @return void
     */
    protected function syncDestinationData($receipts)
    {
        $this->analysis['create'] = $receipts->count();

        foreach ($receipts as $receipt) {
            /** @var User $user */
            $user = User::where('username', $this->getStudentId($receipt))->first();

            if (null === $user) {
                $this->userNotFound($user);
            } elseif (! ReceiptEntity::where('receipt_no', $receipt->receipt_no)->exists()) {
                $user->receipts()->save(new ReceiptEntity([
                    'receipt_no' => $receipt->receipt_no,
                    'receipt_date' => $receipt->receipt_date,
                    'category_id' => $this->getCategoryId($receipt),
                ]))
                    ? ++$this->analysis['created']
                    : ++$this->analysis['fail'];
            }
        }
    }

    /**
     * 取得該收據的使用者學號
     *
     * @param $receipt
     * @return string
     */
    protected function getStudentId($receipt)
    {
        return mb_substr($receipt->payer_name, mb_strpos($receipt->payer_name, ':') + 1, 9);
    }

    protected function userNotFound($user)
    {
        ++$this->analysis['fail'];

        // TODO: Log error
    }

    /**
     * 取得對應類別id.
     *
     * @param $receipt
     * @return int
     */
    protected function getCategoryId($receipt)
    {
        switch (true) {
            case str_contains($receipt->note, '學科'):
                return Category::getCategories('exam.category', 'theory', true);
            case str_contains($receipt->note, '術科'):
                return Category::getCategories('exam.category', 'technology', true);
            default:
                return Category::getCategories('error', 'general', true);
        }
    }
}
