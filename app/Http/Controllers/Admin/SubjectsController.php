<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SubjectsController extends Controller
{
    /**
     * Download subject files.
     *
     * @param Subject $subject
     * @return Response
     */
    public function download(Subject $subject)
    {
        // Assuming subject has a file path or related files
        // You might need to adjust this based on your actual file storage structure
        if (!$subject->file_path || !Storage::exists($subject->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        return Storage::download($subject->file_path, $subject->name . '.' . pathinfo($subject->file_path, PATHINFO_EXTENSION));
    }

    public function update(Request $request, Subject $subject)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        $subject->update([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
        ]);

        // Sync the teachers with the subject
        if (isset($validatedData['teachers'])) {
            $subject->teachers()->sync($validatedData['teachers']);
        } else {
            $subject->teachers()->detach();
        }

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:subjects,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        $subject = Subject::create([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
        ]);

        // Associate teachers with the subject
        if (isset($validatedData['teachers'])) {
            $subject->teachers()->sync($validatedData['teachers']);
        }

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }
}