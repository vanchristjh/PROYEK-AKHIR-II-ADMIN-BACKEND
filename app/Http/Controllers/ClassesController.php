<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ClassesController extends Controller
{
    /**
     * Show the form for creating a new class.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // Try to fetch teachers for the dropdown
            $teachers = Teacher::orderBy('name')->get();
        } catch (QueryException $e) {
            // If teachers table doesn't exist yet
            $teachers = collect([]);
        }
        
        return view('dashboard.classes.create', compact('teachers'));
    }
    
    /**
     * Store a newly created class in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1|max:40',
            'room' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable',
        ];
        
        // Add extra validation for teacher_id if the teachers table exists
        if (Schema::hasTable('teachers')) {
            $rules['teacher_id'] = [
                'nullable',
                Rule::exists('teachers', 'id')
            ];
        }
        
        $validated = $request->validate($rules);
        
        try {
            // Convert empty teacher_id to null
            if (empty($validated['teacher_id'])) {
                $validated['teacher_id'] = null;
            }
            
            // Check if the teacher exists
            if (!is_null($validated['teacher_id'])) {
                $teacherExists = DB::table('teachers')->where('id', $validated['teacher_id'])->exists();
                if (!$teacherExists) {
                    // If teacher doesn't exist, set it to null
                    $validated['teacher_id'] = null;
                }
            }
            
            $class = ClassRoom::create($validated);
            
            return redirect()->route('classes.index')
                ->with('success', 'Kelas berhasil ditambahkan!');
        } catch (QueryException $e) {
            // Log the detailed error
            Log::error('Error creating class: ' . $e->getMessage());
            
            // Check if it's a foreign key constraint error
            if ($e->getCode() == 23000) {
                return back()->withErrors(['teacher_id' => 'Guru yang dipilih tidak valid. Silakan pilih guru yang tersedia.'])
                            ->withInput();
            }
            
            // Handle other database errors
            return back()->withErrors(['database' => 'Terjadi kesalahan database. Silakan coba lagi.'])
                        ->withInput();
        }
    }
    
    // ...existing code...
}