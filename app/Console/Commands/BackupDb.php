<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libs\Generator\MysqlDumpGenerator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup whole tables in database without view tables';

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
            $userDb = config('database.connections.mysql.username');
            $passDb = config('database.connections.mysql.password');
            $dbName = config('database.connections.mysql.database');
            $date = new \DateTime();
            $filename = 'unity_pump_'.$date->format('Ymd_Hi').'.sql.gz';
            
            if (!is_dir(base_path('database/dump/'))) {
                mkdir(base_path('database/dump'));
            }

            $resPath = base_path('database/dump/'.$filename);

            $x = new MysqlDumpGenerator($userDb,$passDb,$dbName,$resPath);
            $command = $x->getShellScript();
            // $process = new Process($command);
            $process = Process::fromShellCommandline($command);

            $process->mustRun();

            $this->info('Backup berhasil.');
        } catch (ProcessFailedException $exception) {
            $this->error('Backup gagal.');
        }
    }
}
