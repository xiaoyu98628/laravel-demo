<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * 加速调试
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public array $data;

    /**
     * Execute the console command.
     */
    public function handle() {}
}
