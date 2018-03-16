<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Infoexam\Eloquent\Models\User;
use Infoexam\Eloquent\Models\Apply;
use Infoexam\Eloquent\Models\Listing;
use Infoexam\Eloquent\Models\Result;
use Infoexam\Eloquent\Models\Certificate;

/**
    系統中不存在帳號將不匯入
    CSV Format: 場次, 學號, 日期, 學術科(T/S), 成績
*/
class ImportOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importolddata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '匯入舊系統成績(CSV)';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $filePath = $this->ask("Imported file path:");
        try {            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found.');
            }

            $fp = fopen($filePath, "r");
            if (!$fp) {
                throw new \Exception('File open failed.');
            }  
        }
        catch(\Exception $e) {
            $this->error($e->getMessage());
            return;
        }
        $this->info("Imported file path: $filePath");
        if ($this->confirm("Import the file? [yes|no]") == false) {
            return;
        }

        $count = 0;
        $exportRowNumber = 0;
        while(($data = fgetcsv($fp, 1000, ",")) !== false) {
            $code = $data[0];
            $username = $data[1];
            $date = $data[2];
            $subjectType = $data[3];
            $score = $data[4];
            $count++;

            $subject = 'app';

            if($this->isCSIE($this->getDepartmentCode($username))) {
                $subject = 'soft';
            }

            $user = User::where('username', $username)->first();

            if($user == null) {
                continue;
            }

            $subjectId = $this->getSubjectId($subject, $subjectType);
            $listing = $this->createListing($code, $date, $subjectId);
            $apply = $this->createApply($user, $listing);
            $result = $this->createResult($apply, $score);
            if($result == null) echo 'NULLLLL';
            $certificate = $this->updateCertificateScore($user, $result, $subjectType);

            $exportRowNumber++;

        }
        //$this->createApply(4263, 27);
        //$this->createListing('11112', '2018/02/14', 23);
        /*$user = new User();
        $user->id = 4263;
        $result = new Result();
        $result->score = 100;
        $this->updateCertificateScore($user, $result, 'S');*/
        $this->info("Finished Rows:$count Actual:$exportRowNumber");
    }

    public function getDepartmentCode($username)
    {
        return substr($username, 3, 3);
    }

    public function isCSIE($deptCode)
    {
        return strcmp($deptCode, '410') == 0;
    }

    public function getListCode()
    {

    }

    public function getSubjectId($subject, $type)
    {
        $subjectId = 0;
        switch($subject) {
            case 'app':
                $subjectId = 21;
                break;
            case 'soft':
                $subjectId = 23;
                break;
        }
        switch($type) {
            case 'T':
                break;
            case 'S':
                $subjectId++;
                break;
        }

        return $subjectId;
    }

    public function createListing($code, $date, $subject_id)
    {
        $listing = Listing::where('code', $code)->first();
        if($listing) {
            return $listing;
        }
        $duration = 90;
        $room = 215;
        $applicable = 0;
        $apply_type_id = 29;
        $maximum_num = 60;
        DB::insert('INSERT INTO listings (code, began_at, ended_at, duration, room, applicable, apply_type_id, subject_id, created_at, updated_at) VALUES (?, ?, ?, ?, ? ,? ,? ,? ,CURRENT_TIMESTAMP ,CURRENT_TIMESTAMP)', [$code, $date, $date, $duration, $room, $applicable, $apply_type_id, $subject_id, $maximum_num]);

        $listing = Listing::where('code', $code)->first();

        return $listing;
    }
    public function createApply(User $user, Listing $listing)
    {
        $apply = new Apply();
        $apply->user_id = $user->id;
        $apply->listing_id = $listing->id;
        $apply->type = 'A';
        $apply->save();

        return $apply;
    }
    public function createResult(Apply $apply, $score)
    {
        $result = new Result();
        $result->apply_id = $apply->id;
        $result->duration = 0;
        $result->score = $score;
        $result->save();

        return $result;
    }

    public function updateCertificateScore(User $user, Result $result, $type)
    {
        $type_id = 0;
        switch($type) {
            case 'T':
                $type_id = 16;
                break;
            case 'S':
                $type_id = 17;
                break;
        }
        $certificate = Certificate::where('user_id', $user->id)->where('category_id', $type_id)->first();
        if($certificate == null) echo 'UserID: ' . $user->id . ' Cate id: ' . $type_id;

        if($certificate->score == null || ($result->score > $certificate->score && $certificate->score >= 0)) {
            $certificate->score = $result->score;
            $certificate->save();
        }

        return $certificate;
    }

}
