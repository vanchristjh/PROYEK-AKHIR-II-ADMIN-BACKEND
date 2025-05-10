<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the student's submissions.
     */
    public function index()
    {
        $user = auth()->user();
        
        $submissions = Submission::where('student_id', $user->student->id)
                                ->with(['assignment', 'assignment.subject'])
                                ->latest('submitted_at')
                                ->get();
        
        return view('siswa.submissions.index', compact('submissions'));
    }

    /**
     * Display the specified submission.
     */
    public function show(Submission $submission)
    {
        // Authorization: only the student who created the submission can view it
        $student = auth()->user()->student;
        
        if (!$student || $submission->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('siswa.submissions.show', compact('submission'));
    }

    /**
     * Store a newly created submission.
     */
    public function store(Request $request, Assignment $assignment)
    {
        // Authorization: only students who are in the assignment's classroom can submit
        $student = auth()->user()->student;
        
        if (!$student || !$assignment->classroom->students->contains($student->id)) {
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('error', 'Anda tidak memiliki akses untuk mengumpulkan tugas ini.');
        }

        // Check if the assignment deadline has passed
        if ($assignment->deadline < now()) {
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('error', 'Deadline pengumpulan tugas telah berakhir.');
        }

        // Check if student has already submitted
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();
            
        if ($existingSubmission) {
            return redirect()->route('siswa.assignments.show', $assignment)
                ->with('error', 'Anda sudah mengumpulkan tugas ini. Silakan edit pengumpulan yang ada.');
        }

        // Validate input
        $validatedData = $request->validate([
            'file' => 'required|file|max:2048', // 2MB max
            'notes' => 'nullable|string|max:500'
        ]);

        // Store file
        $path = $request->file('file')->store('submissions');

        // Create submission
        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'file_path' => $path,
            'notes' => $validatedData['notes'],
            'submitted_at' => now()
        ]);

        return redirect()->route('siswa.assignments.show', $assignment)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    /**
     * Update an existing submission
     */
    public function update(Request $request, Submission $submission)
    {
        // Authorization: only the student who created the submission can update it
        $student = auth()->user()->student;
        
        if (!$student || $submission->student_id !== $student->id) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Anda tidak memiliki akses untuk mengubah pengumpulan ini.');
        }

        // Check if the submission's assignment deadline has passed
        if ($submission->assignment->deadline < now()) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Deadline pengumpulan tugas telah berakhir.');
        }

        // Check if submission is already graded
        if ($submission->score !== null) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Tugas yang sudah dinilai tidak dapat diubah.');
        }

        // Validate input
        $validatedData = $request->validate([
            'file' => 'nullable|file|max:2048', // 2MB max
            'notes' => 'nullable|string|max:500'
        ]);

        // Update submission
        if ($request->hasFile('file')) {
            // Delete old file
            if ($submission->file_path) {
                Storage::delete($submission->file_path);
            }
            
            // Store new file
            $path = $request->file('file')->store('submissions');
            $submission->file_path = $path;
        }

        $submission->notes = $validatedData['notes'];
        $submission->submitted_at = now();
        $submission->save();

        return redirect()->route('siswa.assignments.show', $submission->assignment_id)
            ->with('success', 'Pengumpulan tugas berhasil diperbarui.');
    }

    /**
     * Delete a submission
     */
    public function destroy(Submission $submission)
    {
        // Authorization: only the student who created the submission can delete it
        $student = auth()->user()->student;
        
        if (!$student || $submission->student_id !== $student->id) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Anda tidak memiliki akses untuk menghapus pengumpulan ini.');
        }

        // Check if the submission's assignment deadline has passed
        if ($submission->assignment->deadline < now()) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Deadline pengumpulan tugas telah berakhir.');
        }

        // Check if submission is already graded
        if ($submission->score !== null) {
            return redirect()->route('siswa.assignments.show', $submission->assignment_id)
                ->with('error', 'Tugas yang sudah dinilai tidak dapat dihapus.');
        }

        // Delete file
        if ($submission->file_path) {
            Storage::delete($submission->file_path);
        }

        // Delete submission
        $submission->delete();

        return redirect()->route('siswa.assignments.show', $submission->assignment_id)
            ->with('success', 'Pengumpulan tugas berhasil dihapus.');
    }

    /**
     * Download the submission file.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function download(Submission $submission)
    {
        // Authorization: only the student who created the submission can download it
        $student = auth()->user()->student;
        
        if (!$student || $submission->student_id !== $student->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunduh file ini.');
        }
        
        // Check if file exists
        $filePath = $submission->file_path;
        if (!$filePath || !Storage::exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan atau telah dihapus.');
        }
        
        try {
            return Storage::download($filePath, basename($filePath));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }
}
