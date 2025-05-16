<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckAssignmentsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:assignments-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the structure of the assignments table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('assignments')) {
            $this->error('The assignments table does not exist');
            return 1;
        }

        $this->info('Assignments Table Structure:');
        $this->info('--------------------------');

        $columns = Schema::getColumnListing('assignments');

        foreach ($columns as $column) {
            $columnInfo = DB::select("SHOW COLUMNS FROM assignments WHERE Field = ?", [$column])[0];
            $this->line($column . ' - ' . $columnInfo->Type .
                ($columnInfo->Null === 'NO' ? ' [Required]' : '') .
                (isset($columnInfo->Default) ? ' [Default: ' . $columnInfo->Default . ']' : ''));
        }

        return 0;
    }
}
