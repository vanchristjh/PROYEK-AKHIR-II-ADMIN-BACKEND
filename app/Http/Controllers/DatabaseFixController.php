<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class DatabaseFixController extends Controller
{
    /**
     * Fix missing columns in the schedules table
     */
    public function fixSchedulesTable()
    {
        try {
            if (!Schema::hasColumn('schedules', 'created_by')) {
                Schema::table('schedules', function (Blueprint $table) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('end_time');
                    
                    // Add foreign key constraint
                    $table->foreign('created_by')
                          ->references('id')
                          ->on('users')
                          ->onDelete('set null');
                });
                $message = "created_by column added to schedules table successfully.";
            } else {
                $message = "created_by column already exists in schedules table.";
            }
            
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check and fix the schedules table structure
     */
    public function checkAndFixSchedules()
    {
        $result = [
            'table_exists' => Schema::hasTable('schedules'),
            'columns' => [],
            'actions_taken' => [],
            'success' => false,
        ];
        
        // If table doesn't exist, we can't continue
        if (!$result['table_exists']) {
            $result['message'] = 'Schedules table does not exist!';
            return response()->json($result);
        }
        
        // Check column status
        $columnsToCheck = ['room', 'notes', 'created_by'];
        
        foreach ($columnsToCheck as $column) {
            $result['columns'][$column] = Schema::hasColumn('schedules', $column);
        }
        
        // If all columns exist, we're good
        if (!in_array(false, $result['columns'])) {
            $result['success'] = true;
            $result['message'] = 'All required columns already exist in schedules table.';
            return response()->json($result);
        }
        
        // Otherwise, add missing columns
        try {
            if (!$result['columns']['room']) {
                DB::statement('ALTER TABLE schedules ADD COLUMN room VARCHAR(50) NULL AFTER end_time');
                $result['actions_taken'][] = 'Added room column';
            }
            
            if (!$result['columns']['notes']) {
                DB::statement('ALTER TABLE schedules ADD COLUMN notes TEXT NULL AFTER room');
                $result['actions_taken'][] = 'Added notes column';
            }
            
            if (!$result['columns']['created_by']) {
                DB::statement('ALTER TABLE schedules ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER notes');
                $result['actions_taken'][] = 'Added created_by column';
                
                // Add foreign key if needed
                try {
                    DB::statement('ALTER TABLE schedules ADD CONSTRAINT schedules_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL');
                    $result['actions_taken'][] = 'Added foreign key constraint for created_by';
                } catch (\Exception $e) {
                    $result['actions_taken'][] = 'Failed to add foreign key: ' . $e->getMessage();
                }
            }
            
            $result['success'] = true;
            $result['message'] = 'Fixed schedules table structure successfully.';
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error'] = $e->getMessage();
            $result['message'] = 'Failed to fix schedules table: ' . $e->getMessage();
        }
        
        return response()->json($result);
    }
}
