<?php

namespace App\Console\Commands;

use App\Models\DBBackup as ModelsDBBackup;
use Illuminate\Console\Command;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // date_default_timezone_set("Asia/Calcutta");
        $mysqldump = 'C:\xampp\mysql\bin\mysqldump';
        $backupFileName = 'backup_' . date('Y-m-d') .strtotime(date('h:i:s')). '.sql';
        $path = "/backups/".$backupFileName;
        $command = "$mysqldump --single-transaction --quick --lock-tables=false --extended-insert -u root ledgersinfo > ".storage_path()."/app/public/backups/".$backupFileName;
        exec($command, $output, $resultcode);
        if ($resultcode === 0) {
            ModelsDBBackup::create([
                'name'=> 'backup_' . date('Y-m-d_h:i:s'),
                'file'=> $path
            ]);
            echo "Command executed successfully.";
        } else {
            echo "Command failed with status code: $resultcode";
        }
    }
}
