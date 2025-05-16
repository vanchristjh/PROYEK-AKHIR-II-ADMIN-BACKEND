<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class DatabaseFixController extends Controller
{
    /**
     * Fix teacher_id in assignments table
     */
    public function fixAssignmentsTeacherId()
    {
        // Check if teacher_id column exists
        $hasTeacherId = Schema::hasColumn('assignments', 'teacher_id');
        
        if (!$hasTeacherId) {
            return response()->json([
                'status' => 'error',
                'message' => 'teacher_id column does not exist in assignments table'
            ]);
        }
        
        // Update teacher_id with created_by for all rows where teacher_id is null or 0
        $updated = DB::update('UPDATE assignments SET teacher_id = created_by WHERE teacher_id IS NULL OR teacher_id = 0');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Updated ' . $updated . ' assignments with teacher_id'
        ]);
    }
}
