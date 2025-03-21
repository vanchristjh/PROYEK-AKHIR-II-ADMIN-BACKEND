<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Database\QueryException;

class TeachersController extends Controller
{
    /**
     * Display a listing of the teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $teachers = Teacher::orderBy('name')->paginate(10);
            return view('dashboard.teachers.index', compact('teachers'));
        } catch (QueryException $e) {
            // Table might not exist yet
            return view('dashboard.teachers.index', ['teachers' => collect([])]);
        }
    }

    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Basic validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:20|unique:teachers,nip',
            'email' => 'required|email|unique:teachers,email',
            'phone_number' => 'nullable|string|max:15',
            'gender' => 'nullable|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
        ]);
        
        try {
            $teacher = Teacher::create($validated);
            
            return redirect()->route('teachers.index')
                ->with('success', 'Data guru berhasil ditambahkan!');
        } catch (QueryException $e) {
            return back()->withErrors(['database' => 'Terjadi kesalahan database: ' . $e->getMessage()])
                        ->withInput();
        }
    }
    
    // ...existing code...
}