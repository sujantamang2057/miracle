<?php

namespace App\Console\Commands;

use App\Jobs\PurgeTrashData;
use Illuminate\Console\Command;

class PurgeTrashDataCommand extends Command
{
    protected $signature = 'trash-data:cleanup';

    protected $description = 'Delete old soft-deleted data from the database';

    public function handle()
    {
        PurgeTrashData::dispatch();
        $this->info('Old soft-deleted data cleanup job dispatched.');
    }
}
