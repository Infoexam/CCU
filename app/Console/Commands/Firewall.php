<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use M6Web\Component\Firewall\Entry\EntryFactory;

class Firewall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firewall 
                            {--l|list : 列出所有防火牆規則}
                            {--a|add : 新增規則}
                            {--d|delete : 刪除規則}
                            {--c|clear : 清空規則}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '管理網佔防火牆規則';

    /**
     * The firewall rules.
     *
     * @var Collection
     */
    protected $rules;

    /**
     * Whether the rule is modified or not.
     *
     * @var bool
     */
    protected $touched = false;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->rules = Cache::tags('config')->get('iptables', new Collection());

        if ($this->option('add')) {
            $this->add();
        } elseif ($this->option('delete')) {
            $this->delete();
        } elseif ($this->option('clear')) {
            $this->clear();
        }

        if ($this->touched) {
            $this->rules = $this->rules->sortBy('index', SORT_NUMERIC)->values();

            Cache::tags('config')->forever('iptables', $this->rules);
        }

        $this->table(['#', 'IP 位址', '目標'], $this->rules);
    }

    /**
     * Add a new rule to firewall rules.
     *
     * @return void
     */
    protected function add()
    {
        $type = $this->choice('於指定位置插入規則或附加於尾端？', ['插入規則', '附加規則'], 1);

        $index = ('插入規則' === $type) ? $this->promptForIndex() : PHP_INT_MAX;

        $target = $this->choice('適用於管理頁面或考試頁面？', ['管理頁面', '考試頁面']);

        if (PHP_INT_MAX !== $index) {
            $this->reindex($index);
        } else {
            $index = ($this->rules->max('index') ?? 0) + 1;
        }

        $this->rules->push([
            'index' => $index,
            'ip' => $this->promptForIp(),
            'role' => '管理頁面' === $target ? 'admin' : 'testing',
        ]);

        $this->touched = true;

        $this->info('新增成功');
    }

    /**
     * Get insert index.
     *
     * @return int
     */
    protected function promptForIndex()
    {
        $index = intval($this->ask('插入位置？'));

        return $index > 0 ? $index : PHP_INT_MAX;
    }

    /**
     * Get insert ip.
     *
     * @return string
     */
    protected function promptForIp()
    {
        $entry = new EntryFactory();

        do {
            $ip = $this->ask('IP 位址，支援 Wild Card、CIDR Mask 以及 Subnet Mask');

            if (false !== $entry->getEntry($ip)) {
                break;
            }

            $this->error('不合法的 IP 位址');
        } while (true);

        return $ip;
    }

    /**
     * Delete the specific rule.
     *
     * @return void
     */
    protected function delete()
    {
        $index = intval($this->ask('刪除位置？'));

        if ($index <= 0 || ! $this->rules->has($index - 1)) {
            $this->error('不合法的位置');

            return;
        }

        $this->rules->forget($index - 1);

        $this->reindex($index, -1);

        $this->touched = true;

        $this->info('刪除成功');
    }

    /**
     * Clear all firewall rules.
     *
     * @return void
     */
    protected function clear()
    {
        if ($this->confirm('確認清除所有防火牆規則？')) {
            $this->rules = new Collection();

            $this->touched = true;

            $this->info('清除成功');
        }
    }

    /**
     * Reindex the rules.
     *
     * @param int $boundary
     * @param int $step
     *
     * @return void
     */
    protected function reindex($boundary, $step = 1)
    {
        $this->rules->transform(function ($rule) use ($boundary, $step) {
            if ($rule['index'] >= $boundary) {
                $rule['index'] += $step;
            }

            return $rule;
        });
    }

    /**
     * Write a string as information output.
     *
     * @param  string  $string
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function info($string, $verbosity = null)
    {
        parent::info($string.PHP_EOL, $verbosity);
    }
}
