<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index()
    {
        $subjects = Subject::withCount('teachers')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        // Get only users with role_id = 2 (Teacher role)
        $teachers = \App\Models\User::where('role_id', 2)->orderBy('name')->get();
        
        return view('admin.subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created subject.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $subject = Subject::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            if (!empty($validated['teachers'])) {
                $subject->teachers()->attach($validated['teachers']);
            }

            DB::commit();
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        // Load all necessary relationships
        $subject->load(['teachers', 'classrooms']);
        
        // Initialize empty collections for relationships that might not exist
        if (!$subject->relationLoaded('materials')) {
            $subject->setRelation('materials', collect());
        }
        if (!$subject->relationLoaded('assignments')) {
            $subject->setRelation('assignments', collect());
        }
        
        return view('admin.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the subject.
     */
    public function edit(Subject $subject)
    {
        // Based on the database queries from the error trace,
        // It seems there's a roles table with a relationship to users
        // Let's try this approach:
        
        // Assuming there's a role_id in the users table
        $teachers = User::where('role_id', 2)->get(); // 2 is likely the ID for teacher role
        
        // If the above doesn't work, try using role_user pivot table
        // $teachers = DB::table('users')
        //     ->join('role_user', 'users.id', '=', 'role_user.user_id')
        //     ->join('roles', 'roles.id', '=', 'role_user.role_id')
        //     ->where('roles.name', 'teacher')
        //     ->select('users.*')
        //     ->get();
        
        // If none of these work, we could simply pass all users as a fallback
        // $teachers = User::all();
        
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the subject.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $subject->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            // Sync the teachers
            $teachers = $request->input('teachers', []);
            $subject->teachers()->sync($teachers);

            DB::commit();
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the subject.
     */
    public function destroy(Subject $subject)
    {
        DB::beginTransaction();
        try {
            // Detach all teachers before deleting
            $subject->teachers()->detach();
            $subject->delete();
            
            DB::commit();
            return redirect()->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download subject files.
     *
     * @param Subject $subject
     * @return Response
     */
    public function download(Subject $subject)
    {
        // Assuming subject has a file path or related files
        if (!$subject->file_path || !Storage::exists($subject->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        return Storage::download($subject->file_path, $subject->name . '.' . pathinfo($subject->file_path, PATHINFO_EXTENSION));
    }
}
