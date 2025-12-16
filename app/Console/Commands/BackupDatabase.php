<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backup the database and store it in directory database-backup';

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
        $filename= 'backup-'. Carbon::now()->format('Y-m-d-H-i-s').'.sql';
        $storagePath = '/home/u984881745/domains/technostation.org/backups';


        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $filePath =$storagePath."/".$filename;

        $dbHost = '127.0.0.1';
        $dbPort = '3306';

        $dbName='u984881745_digitalstation' ;
        $dbUser='u984881745_digitalstation' ;
        $dbPass='Digitalstation1';
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} --port={$dbPort} {$dbName} > {$filePath}";


        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Backup successfully created at: " . $filePath);
        } else {
            $this->error("Backup failed. Please check permissions.");
        }

    }
}
