<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\ProcessTest;

// Generate database view
class QueueTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue test';

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
        // Send "ProcessTest" into queue
        // "Test" is parameter of __construct($text)
        ProcessTest::dispatch('Test');
    }
}
