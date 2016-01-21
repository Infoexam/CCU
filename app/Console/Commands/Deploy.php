<?php

namespace App\Console\Commands;

use App\Infoexam\Core\Entity;
use Artisan;
use Cache;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Log;
use RuntimeException;
use Symfony\Component\Process\Process;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy {--isRestart}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy application';

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
        $this->call('down');

        $this->clearCache();

        if (! $this->option('isRestart')) {
            $this->composerUpdate();

            if ($this->queueNeedRestart()) {
                $this->call('up');

                return;
            }
        }

        $this->migrate();

        $this->copyAssets();

        $this->setupCache();

        $this->call('up');

        Log::info('Github Webhook', ['status' => 'update successfully']);
    }

    /**
     * composer related update
     */
    protected function composerUpdate()
    {
        // 取得 composer 路徑
        $path = config('infoexam.composer_path');

        if (! empty($path)) {
            // 設置 composer home 目錄
            $this->setComposerHome();

            // 如果超過 15 天未更新，則先更新 composer 本身
            if (Carbon::now()->diffInDays(Carbon::createFromTimestamp(File::lastModified($path))) > 15) {
                $this->externalCommand("{$path} self-update");
            }

            // 執行 package 更新
            $this->externalCommand("git pull; {$path} install --no-scripts --no-dev -o");
        }
    }

    /**
     * 設置 composer home 環境變數
     *
     * @return void
     */
    protected function setComposerHome()
    {
        $dir = config('infoexam.composer_home');

        if (empty($dir)) {
            $dir = file_build_path(sys_get_temp_dir(), 'composer-temp-dir');

            if (! File::exists($dir)) {
                File::makeDirectory($dir);
            }
        }

        putenv("HOME={$dir}");
        putenv("COMPOSER_HOME={$dir}");
    }

    /**
     * 判斷是否要重新啟動 queue
     *
     * @return bool
     */
    protected function queueNeedRestart()
    {
        switch (true) {
            case 'production' === config('app.env'):
            case $this->isModified(app_path(file_build_path('Console', 'Commands', 'Deploy.php'))):
            case $this->isModified(base_path('composer.lock')):
                $this->call('queue:restart');

                Artisan::queue('deploy', ['--isRestart' => true]);

                return true;
            default :
                return false;
        }
    }

    /**
     *  Migrate database.
     *
     * @return void
     */
    protected function migrate()
    {
        $migrations = count(File::files(database_path('migrations')));

        if ($migrations > Cache::tags('deploy')->get('migrations', 0)) {
            Cache::tags('deploy')->forever('migrations', $migrations);

            $this->call('migrate', ['--force' => true]);
        }
    }

    /**
     * 複製 assets 到 static 資料夾
     *
     * @return void
     */
    protected function copyAssets()
    {
        $targetDir = config('infoexam.assets_dir');

        if (empty($targetDir)) {
            return;
        }

        $version = config('app.env') === 'production' ? Entity::VERSION : 'dev';

        File::copyDirectory(public_path(file_build_path('assets', 'css')), file_build_path($targetDir, 'css', $version));
        File::copyDirectory(public_path(file_build_path('assets', 'js')), file_build_path($targetDir, 'js', $version));
    }

    /**
     * 清除快取
     *
     * @return void
     */
    protected function clearCache()
    {
        $this->call('route:clear');

        $this->call('config:clear');

        $this->call('view:clear');

        $this->call('clear-compiled');
    }

    /**
     * 設置快取
     *
     * @return void
     */
    protected function setupCache()
    {
        $this->call('route:cache');

        $this->call('config:cache');

        $this->call('clear-compiled');
        $this->call('optimize');
    }

    /**
     * 執行外部程式指令
     *
     * @param string $command
     * @return string
     */
    protected function externalCommand($command)
    {
        $process = new Process($command);

        $process->setWorkingDirectory(base_path());

        $process->run();

        if ( ! $process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * 檢查指定檔案是否更改過
     *
     * @param string $path
     * @return bool
     */
    protected function isModified($path)
    {
        if (($last = File::lastModified($path)) > Cache::tags('deploy')->get(md5($path), 0)) {
            Cache::tags('deploy')->forever(md5($path), $last);

            return true;
        }

        return false;
    }
}
