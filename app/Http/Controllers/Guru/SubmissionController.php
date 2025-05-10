<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions for a specific assignment.
     */
    public function index(Assignment $assignment)
    {
        // Authorization check - only assignment creator or admin can access
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Get all students in the classroom
        $students = $assignment->classroom->students;
        $totalStudents = $students->count();

        // Get submissions with student info
        $query = DB::table('submissions')
            ->leftJoin('students', 'submissions.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('submissions.assignment_id', $assignment->id)
            ->select(
                'submissions.*',
                'students.nis as student_nis',
                'users.name as student_name',
                DB::raw('(submissions.id IS NOT NULL) as submitted')
            );

        // Apply filters
        if (request('search')) {
            $search = '%' . request('search') . '%';
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', $search)
                  ->orWhere('students.nis', 'like', $search);
            });
        }

        if (request('status') === 'graded') {
            $query->whereNotNull('submissions.score');
        } elseif (request('status') === 'ungraded') {
            $query->whereNull('submissions.score')->whereNotNull('submissions.id');
        } elseif (request('status') === 'late') {
            $query->whereRaw('submissions.submitted_at > ?', [$assignment->deadline]);
        } elseif (request('status') === 'ontime') {
            $query->whereRaw('submissions.submitted_at <= ?', [$assignment->deadline]);
        }

        $submissions = $query->paginate(15);

        // If we need to show students who haven't submitted
        if (request('show_nosubmit', false)) {
            $submittedStudentIds = $submissions->pluck('student_id')->toArray();
            $nonSubmittedStudents = $students->whereNotIn('id', $submittedStudentIds);
            
            // Create virtual submission objects for non-submitted students
            $nonSubmittedData = [];
            foreach ($nonSubmittedStudents as $student) {
                $nonSubmittedData[] = (object)[
                    'id' => null,
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                    'file_path' => null,
                    'submitted_at' => null,
                    'score' => null,
                    'feedback' => null,
                    'notes' => null,
                    'updated_at' => null,
                    'created_at' => null,
                    'student_nis' => $student->nis,
                    'student_name' => $student->user->name,
                    'submitted' => false
                ];
            }

            // Combine and paginate manually if needed
            $allEntries = array_merge($submissions->items(), $nonSubmittedData);
            $submissions = $submissions->setCollection(collect($allEntries));
        }

        return view('guru.submissions.index', [
            'assignment' => $assignment,
            'submissions' => $submissions,
            'totalStudents' => $totalStudents
        ]);
    }

    /**
     * Show the form for grading a specific submission.
     */
    public function show(Assignment $assignment, Submission $submission)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if submission belongs to the assignment
        if ($submission->assignment_id !== $assignment->id) {
            return redirect()->route('guru.submissions.index', $assignment->id)
                ->with('error', 'Pengumpulan tugas tidak valid.');
        }

        // Load the student relationship
        $submission->load('student.user');

        return view('guru.submissions.show', [
            'assignment' => $assignment,
            'submission' => $submission
        ]);
    }

    /**
     * Grade a submission.
     */
    public function grade(Request $request, Assignment $assignment, Submission $submission)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Validate request
        $validatedData = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assignment->max_score,
            'feedback' => 'nullable|string|max:1000'
        ]);

        // Update the submission with the score
        $submission->score = $validatedData['score'];
        $submission->feedback = $validatedData['feedback'];
        $submission->save();

        return redirect()->route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id])
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Download a submission file.
     */
    public function download(Assignment $assignment, Submission $submission)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if file exists
        if (!$submission->file_path) {
            return redirect()->route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id])
                ->with('error', 'File tidak ditemukan.');
        }

        // Get file path and attempt to download
        $filePath = $submission->file_path;
        if (Storage::exists($filePath)) {
            $filename = basename($filePath);
            return Storage::download($filePath, $filename);
        }

        return redirect()->route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id])
            ->with('error', 'File tidak ditemukan di server.');
    }

    /**
     * Preview a submission file.
     */
    public function preview(Assignment $assignment, Submission $submission)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if file exists
        if (!$submission->file_path) {
            return redirect()->route('guru.submissions.show', ['assignment' => $assignment->id, 'submission' => $submission->id])
                ->with('error', 'File tidak ditemukan.');
        }

        // Get file path and check if it's previewable
        $filePath = $submission->file_path;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Previewable extensions - can be expanded based on your requirements
        $previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($extension), $previewableExtensions) && Storage::exists($filePath)) {
            return response()->file(Storage::path($filePath));
        }

        return redirect()->route('guru.submissions.download', ['assignment' => $assignment->id, 'submission' => $submission->id])
            ->with('info', 'File tidak dapat dipratinjau secara langsung. Mengunduh file...');
    }

    /**
     * Mass grade multiple submissions.
     */
    public function massGrade(Request $request, Assignment $assignment)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Validate request
        $validatedData = $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:submissions,id',
            'mass_score' => 'required|numeric|min:0|max:' . $assignment->max_score,
            'mass_feedback' => 'nullable|string|max:1000'
        ]);

        // Update all selected submissions
        Submission::whereIn('id', $validatedData['submission_ids'])
            ->update([
                'score' => $validatedData['mass_score'],
                'feedback' => $validatedData['mass_feedback']
            ]);

        $count = count($validatedData['submission_ids']);
        return redirect()->route('guru.submissions.index', $assignment->id)
            ->with('success', "$count pengumpulan berhasil dinilai.");
    }

    /**
     * Give a zero score to a student who didn't submit.
     */
    public function markZero(Request $request, Assignment $assignment, Student $student)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if student is in the class
        $studentInClass = $assignment->classroom->students->contains($student->id);
        if (!$studentInClass) {
            return redirect()->route('guru.submissions.index', $assignment->id)
                ->with('error', 'Siswa tidak terdaftar di kelas ini.');
        }

        // Check if submission already exists
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            $existingSubmission->score = 0;
            $existingSubmission->feedback = 'Tidak mengumpulkan tugas.';
            $existingSubmission->save();
        } else {
            // Create a "no submission" record with zero score
            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'submitted_at' => now(),
                'score' => 0,
                'feedback' => 'Tidak mengumpulkan tugas.'
            ]);
        }

        return redirect()->route('guru.submissions.index', $assignment->id)
            ->with('success', 'Nilai 0 berhasil diberikan kepada siswa.');
    }

    /**
     * Export submissions and grades to Excel.
     */
    public function export(Assignment $assignment)
    {
        // Authorization check
        if (auth()->user()->id !== $assignment->created_by && !auth()->user()->isAdmin()) {
            return redirect()->route('guru.assignments.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Get all students in the classroom
        $students = $assignment->classroom->students()->with('user')->get();
        
        // Get all submissions for this assignment
        $submissions = Submission::where('assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');
            
        // Create the CSV data
        $csvData = [
            ['No.', 'NIS', 'Nama Siswa', 'Status Pengumpulan', 'Waktu Pengumpulan', 'Terlambat', 'Nilai']
        ];
        
        foreach ($students as $index => $student) {
            $submission = $submissions->get($student->id);
            
            $status = 'Belum Mengumpulkan';
            $submittedAt = '-';
            $isLate = '-';
            $score = '-';
            
            if ($submission) {
                $status = 'Sudah Mengumpulkan';
                $submittedAt = $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i:s') : '-';
                $isLate = $submission->submitted_at && $submission->submitted_at->gt($assignment->deadline) ? 'Ya' : 'Tidak';
                $score = $submission->score !== null ? $submission->score : 'Belum Dinilai';
            }
            
            $csvData[] = [
                $index + 1,
                $student->nis,
                $student->user->name,
                $status,
                $submittedAt,
                $isLate,
                $score
            ];
        }
        
        // Generate file name
        $fileName = 'Nilai_' . str_replace(' ', '_', $assignment->title) . '_' . date('Y-m-d') . '.csv';
        
        // Create and return CSV file
        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }
}
