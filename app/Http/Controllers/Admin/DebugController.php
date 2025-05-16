<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DebugController extends Controller
{
    /**
     * Show database schema for debugging
     */
    public function schema()
    {
        // Get columns for key tables
        $userColumns = Schema::getColumnListing('users');
        $roleColumns = Schema::hasTable('roles') ? Schema::getColumnListing('roles') : [];
        $roleUserColumns = Schema::hasTable('role_user') ? Schema::getColumnListing('role_user') : [];
        $subjectColumns = Schema::hasTable('subjects') ? Schema::getColumnListing('subjects') : [];
        $subjectTeacherColumns = Schema::hasTable('subject_teacher') ? Schema::getColumnListing('subject_teacher') : [];
        
        return view('admin.debug.schema', compact(
            'userColumns',
            'roleColumns',
            'roleUserColumns',
            'subjectColumns',
            'subjectTeacherColumns'
        ));
    }
}
