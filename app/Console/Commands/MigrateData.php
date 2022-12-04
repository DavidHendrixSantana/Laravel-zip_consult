<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\InsertData;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the xls information to jobs which going to migrate the data to the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Scan the name of the xls files  in the folder Excel
        $pathNames = array_slice(scandir(public_path('Excel')),2); 

        //iterating the xls files to generate a job that migrates each sheet
        foreach ($pathNames as $file) {
          InsertData::dispatch($file);
        }
        $this->info("----Jobs created----");
        $this->info("Now execute the command: php artisan queue:listen");
    }
}
