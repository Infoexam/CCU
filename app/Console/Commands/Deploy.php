<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

        $this->externalCommand('composer install -o');

        $this->externalCommand('gulp --production');

        $this->call('migrate', ['--force' => true]);

        $this->call('up');
    }

    protected function externalCommand($command)
    {
        $process = new Process($command);

        $process->setWorkingDirectory(base_path());

        $process->run();

        if ( ! $process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
