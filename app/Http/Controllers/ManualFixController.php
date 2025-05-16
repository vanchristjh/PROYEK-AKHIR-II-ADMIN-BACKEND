<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManualFixController extends Controller
{
    /**
     * Fix missing columns in schedules table.
     *
     * @return \Illuminate\Http\Response
     */
    public function fixSchedulesTable()
    {
        try {
            // Check if columns exist
            $hasRoom = Schema::hasColumn('schedules', 'room');
            $hasNotes = Schema::hasColumn('schedules', 'notes');
            $hasCreatedBy = Schema::hasColumn('schedules', 'created_by');
            
            // Add missing columns
            if (!$hasRoom) {
                DB::statement('ALTER TABLE schedules ADD COLUMN room VARCHAR(50) NULL AFTER end_time');
            }
            
            if (!$hasNotes) {
                DB::statement('ALTER TABLE schedules ADD COLUMN notes TEXT NULL AFTER room');
            }
            
            if (!$hasCreatedBy) {
                DB::statement('ALTER TABLE schedules ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER notes');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Kolom berhasil ditambahkan ke tabel schedules',
                'added_columns' => [
                    'room' => !$hasRoom,
                    'notes' => !$hasNotes,
                    'created_by' => !$hasCreatedBy
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kolom: ' . $e->getMessage()
            ], 500);
        }
    }
}
