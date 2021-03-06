<?php

namespace App\Console\Commands;

use App;
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
     * The environment is production or not.
     *
     * @var bool
     */
    protected $production = false;

    /**
     * Deploy constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->production = App::environment('production');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->down();

        if (! $this->option('isRestart')) {
            $this->composerUpdate();

            if ($this->queueNeedRestart()) {
                $this->call('up');

                return;
            }
        }

        $this->migrate();

        if ($this->production) {
            $this->copyAssets();
        }

        $this->up();

        Log::info('Github-Webhook', ['status' => 'ok']);
    }

    /**
     * composer related update.
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
            $this->externalCommand("git fetch; git reset --hard origin/master; {$path} install --no-scripts --no-dev -o");
        }
    }

    /**
     * 設置 composer home 環境變數.
     *
     * @return void
     */
    protected function setComposerHome()
    {
        $dir = config('infoexam.composer_home');

        if (empty($dir)) {
            $dir = sys_get_temp_dir().'/composer-temp-dir';

            if (! File::exists($dir)) {
                File::makeDirectory($dir);
            }
        }

        putenv("HOME={$dir}");
        putenv("COMPOSER_HOME={$dir}");
    }

    /**
     * 判斷是否要重新啟動 queue.
     *
     * @return bool
     */
    protected function queueNeedRestart()
    {
        switch (true) {
            case $this->production:
            case $this->isModified(__FILE__):
            case $this->isModified(base_path('composer.lock')):
                $this->call('queue:restart');

                Artisan::queue('deploy', ['--isRestart' => true]);

                return true;
            default:
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
     * 複製 assets 到 static 資料夾.
     *
     * @return void
     */
    protected function copyAssets()
    {
        $targetDir = config('infoexam.static_dir');

        if (empty($targetDir)) {
            return;
        }

        $version = json_decode(File::get(base_path('composer.json'), true), true)['version'];

        File::copyDirectory(
            public_path('assets'),
            "{$targetDir}/assets/{$version}"
        );
    }

    /**
     * Put the application into maintenance mode.
     *
     * @return void
     */
    protected function down()
    {
        $this->clearCache();

        $this->call('down');
    }

    /**
     * Bring the application out of maintenance mode.
     *
     * @return void
     */
    protected function up()
    {
        $this->call('up');

        $this->setupCache();
    }

    /**
     * 清除快取.
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
     * 設置快取.
     *
     * @return void
     */
    protected function setupCache()
    {
        $this->externalCommand('php artisan api:cache');

        $this->externalCommand('php artisan config:cache');

        $this->externalCommand('php artisan clear-compiled');

        $this->externalCommand('php artisan optimize');
    }

    /**
     * 執行外部程式指令.
     *
     * @param string $command
     * @return string
     */
    protected function externalCommand($command)
    {
        $process = new Process($command);

        $process->setTimeout(300);

        $process->setWorkingDirectory(base_path());

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * 檢查指定檔案是否更改過.
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
