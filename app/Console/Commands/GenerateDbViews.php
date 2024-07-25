<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

// Generate database view
class GenerateDbViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-db-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate database view';

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
        try {
            DB::beginTransaction();

            $this->info('Generating database view...');
            $this->generateStockFlow();
            $this->info('Generating database view completed!');
            DB::commit();
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            DB::rollBack();
        }
    }
    
    private function generateStockFlow()
    {
        $this->info('Generating view_stock_flow view...');
        // Get sql script from file
        $sql = file_get_contents(database_path('sql/GenerateView.sql'));

        DB::unprepared($sql);
    }
}
