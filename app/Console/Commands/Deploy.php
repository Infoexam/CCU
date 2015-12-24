<?php

namespace App\Console\Commands;

use Cache;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Log;
use Symfony\Component\Process\Process;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

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

        $this->vendorsUpdate();

        $this->migrate();

        $this->call('route:cache');

        $this->call('config:cache');

        $this->call('up');

        Log::info('Github Webhook', ['status' => 'update successfully']);

        if ($this->isModified(app_path('Console/Commands/Deploy.php'))) {
            $this->call('queue:restart');
        }
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
     * 更新 vendors
     *
     * @return void
     */
    protected function vendorsUpdate()
    {
        $this->composerUpdate();

        $this->npmUpdate();

        File::delete(array_merge(
            File::files(base_path('resources/assets/js/compiled')),
            File::files(public_path('css')),
            File::files(public_path('js'))
        ));

        $this->externalCommand('gulp --production');
    }

    /**
     * composer related update
     */
    protected function composerUpdate()
    {
        if (! $this->isModified(base_path('composer.lock'))) {
            $this->externalCommand('git pull');
        } else {
            // 取得 composer 路徑
            $path = trim($this->externalCommand('which composer'));

            if (! empty($path)) {
                // 如果超過 15 天未更新，則先更新 composer 本身
                if (Carbon::now()->diffInDays(Carbon::createFromTimestamp(File::lastModified($path))) > 15) {
                    $this->externalCommand("{$path} self-update");
                }

                // 如 home 目錄尚未設置，則指定為暫存目錄
                if (empty(env('COMPOSER_HOME', ''))) {
                    $dir = sys_get_temp_dir() . '/composer-' . str_random(8);

                    File::makeDirectory($dir);

                    putenv("COMPOSER_HOME={$dir}");
                }

                // 執行 package 更新
                $this->externalCommand("git pull; {$path} install -o");

                // 如是用暫存目錄，則更新完後將暫存目錄移除
                if (isset($dir)) {
                    File::deleteDirectory($dir);
                }
            }
        }
    }

    /**
     * npm related update
     */
    protected function npmUpdate()
    {
        if ($this->isModified(base_path('package.json'))) {
            $this->externalCommand('npm install');
        }
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
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
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
