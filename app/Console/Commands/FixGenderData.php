<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FixGenderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-gender-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix gender data in users table to match expected format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix gender data...');
        
        // Get all users with gender values that need to be fixed
        $count = DB::table('users')
            ->whereIn('gender', ['male', 'female', 'laki-laki', 'perempuan'])
            ->update([
                'gender' => DB::raw("CASE 
                    WHEN gender = 'male' OR gender = 'laki-laki' THEN 'L' 
                    WHEN gender = 'female' OR gender = 'perempuan' THEN 'P' 
                    ELSE gender 
                END")
            ]);
        
        $this->info("Fixed {$count} records successfully!");
        
        return Command::SUCCESS;
    }
}
