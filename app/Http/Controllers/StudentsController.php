<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Database\QueryException;

class StudentsController extends Controller
{
    // ...existing code...
    
    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // Try to fetch all classes to populate the dropdown
            $classes = ClassRoom::orderBy('name')->get();
        } catch (QueryException $e) {
            // If classes table doesn't exist yet
            $classes = collect([]);
        }
        
        return view('dashboard.students.create', compact('classes'));
    }
    
    // ...existing code...
}