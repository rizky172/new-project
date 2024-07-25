<?php
namespace App\Libs\Generator;

use App\Libs\Repository\AbstractRepository;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
// use Symfony\Component\Console\Output\ConsoleOutput;
use DB;

class MysqlDumpGenerator extends AbstractRepository
{
    private $userDb;
    private $passDb;
    private $dbName;
    private $resPath;
    
    public function __construct($userDb, $passDb, $dbName, $resPath)
    {
        $this->userDb = $userDb;
        $this->passDb = $passDb;
        $this->dbName = $dbName;
        $this->resPath = $resPath;
    }

    public function getShellScript()
    {
        $viewTbl = DB::select("
            SHOW FULL TABLES 
            IN ".$this->dbName." 
            WHERE TABLE_TYPE LIKE 'VIEW';"
        );

        $tables = array_map('current', $viewTbl);
        $views = '';
        foreach ($tables as $key => $v) {
            $views .= ' --ignore-table='.$this->dbName.'.'.$v;
        }

        /*$process =  new Process(sprintf(
            'mysqldump -u%s -p%s %s '.$views.'| gzip -9 -c > %s',
            $this->userDb,
            $this->passDb,
            $this->dbName,
            $this->resPath.$filename
        ));
        $process->mustRun();*/

        return sprintf(
            "mysqldump -u%s -p'%s' %s ".$views." | gzip -9 -c > %s",
            $this->userDb,
            $this->passDb,
            $this->dbName,
            $this->resPath
        );
    }
}
