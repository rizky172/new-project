<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

use App\Libs\Generator\DatabaseRandomizeGenerator;

class RandomizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:randomize-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomize database data like real person name, company, etc';

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
        $this->info('Randomize database data...');
        $repo = new DatabaseRandomizeGenerator();
        $repo->generate();
        $this->info('Randomize database data COMPLETED!');
    }
}
