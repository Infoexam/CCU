<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SubresourceIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sri {algo=sha384 : Hash algorithm} {--override : Override the home.blade.php integrity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the resources integrity.';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->validate();

        foreach ($this->integrity() as $filename => $integrity) {
            if (!$this->option('override')) {
                $this->line("<info>$filename</info>:<comment>$integrity</comment>");
            } else {
                $patterns[] = '/('.preg_quote($filename).'.+integrity=")(.*)"/U';

                $replacements[] = '${1}'.$integrity.'"';
            }
        }

        if ($this->option('override') && isset($patterns, $replacements)) {
            $path = view('home')->getPath();

            $content = preg_replace(
                $patterns,
                $replacements,
                $this->filesystem->get($path, true)
            );

            $this->filesystem->put($path, $content, true);

            $this->info('Successfully update the integrity.');
        }
    }

    /**
     * Validate the arguments.
     */
    protected function validate()
    {
        if (!in_array($this->argument('algo'), ['sha256', 'sha384', 'sha512'])) {
            $this->error('Currently the allowed hash algorithm are sha256, sha384 and sha512.');

            exit(1);
        }
    }

    /**
     * Get files' integrity.
     *
     * @return array
     */
    protected function integrity()
    {
        $integrity = [];

        foreach ($this->files() as $file) {
            $content = $this->filesystem->get($file, true);

            $hash = hash($this->argument('algo'), $content, true);

            $encode = base64_encode($hash);

            $value = $this->argument('algo').'-'.$encode;

            $filename = $this->filesystem->basename($file);

            if (isset($integrity[$filename])) {
                $integrity[$filename] .= ' '.$value;
            } else {
                $integrity[$filename] = $value;
            }
        }

        return $integrity;
    }

    /**
     * Get files that should be calculated.
     *
     * @return array
     */
    protected function files()
    {
        $files = [];

        $assets = ['css', 'js'];

        foreach ($assets as $asset) {
            $path = public_path("assets/{$asset}");

            $files = array_merge($files, $this->filesystem->files($path));
        }

        return array_merge($files, [
            public_path('js/main.css'),
            public_path('js/main.js'),
            public_path('js/vendor.js'),
        ]);
    }
}
