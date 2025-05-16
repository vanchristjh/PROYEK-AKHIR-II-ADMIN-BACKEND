<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the submissions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $submissions = Submission::where('student_id', auth()->id())
            ->with(['assignment.subject', 'assignment.teacher'])
            ->latest('submitted_at')
            ->paginate(10);

        return view('siswa.submissions.index', compact('submissions'));
    }

    /**
     * Display the specified submission.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $submission = Submission::findOrFail($id);

        if ($submission->student_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('siswa.submissions.show', compact('submission'));
    }

    /**
     * Store a new submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'assignment_id' => 'required|exists:assignments,id'
        ]);

        $assignment = Assignment::findOrFail($request->assignment_id);
        if (Carbon::now() > $assignment->deadline) {
            return redirect()->back()->with('error', 'Tidak dapat mengumpulkan tugas yang telah melewati deadline.');
        }

        $existingSubmission = Submission::where('assignment_id', $request->assignment_id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existingSubmission) {
            return redirect()->back()->with('error', 'Anda sudah mengumpulkan tugas ini. Silakan edit pengumpulan yang ada.');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('submissions/' . $assignment->id, 'public');

            $fileSize = $this->formatFileSize($file->getSize());
            $fileExtension = $file->getClientOriginalExtension();

            $submission = new Submission();
            $submission->assignment_id = $request->assignment_id;
            $submission->student_id = Auth::id();
            $submission->file_path = $filePath;
            $submission->file_name = $file->getClientOriginalName();
            $submission->file_type = $file->getMimeType();
            $submission->file_size = $fileSize;
            $submission->file_icon = $this->getFileIconClass($fileExtension);
            $submission->file_color = $this->getFileColorClass($fileExtension);
            $submission->submitted_at = Carbon::now();
            $submission->save();

            return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    /**
     * Update an existing submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
        ]);

        $submission = Submission::findOrFail($id);

        if ($submission->student_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit pengumpulan ini.');
        }

        $assignment = $submission->assignment;
        if (Carbon::now() > $assignment->deadline) {
            return redirect()->back()->with('error', 'Tidak dapat mengedit pengumpulan untuk tugas yang telah melewati deadline.');
        }

        if ($submission->score !== null) {
            return redirect()->back()->with('error', 'Tidak dapat mengedit pengumpulan yang sudah dinilai.');
        }

        if ($request->hasFile('file')) {
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('submissions/' . $submission->assignment_id, 'public');

            $fileSize = $this->formatFileSize($file->getSize());
            $fileExtension = $file->getClientOriginalExtension();

            $submission->file_path = $filePath;
            $submission->file_name = $file->getClientOriginalName();
            $submission->file_type = $file->getMimeType();
            $submission->file_size = $fileSize;
            $submission->file_icon = $this->getFileIconClass($fileExtension);
            $submission->file_color = $this->getFileColorClass($fileExtension);
            $submission->updated_at = Carbon::now();
            $submission->save();

            return redirect()->back()->with('success', 'Pengumpulan tugas berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    /**
     * Remove a submission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);

        if ($submission->student_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus pengumpulan ini.');
        }

        $assignment = $submission->assignment;
        if (Carbon::now() > $assignment->deadline) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus pengumpulan untuk tugas yang telah melewati deadline.');
        }

        if ($submission->score !== null) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus pengumpulan yang sudah dinilai.');
        }

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return redirect()->back()->with('success', 'Pengumpulan tugas berhasil dihapus.');
    }

    /**
     * Download submission file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $submission = Submission::findOrFail($id);

        $user = Auth::user();
        $isStudent = $user->role === 'student';
        $isTeacher = $user->role === 'teacher';

        if (($isStudent && $submission->student_id != $user->id) &&
            !($isTeacher && $submission->assignment->subject->teachers->contains($user->id))) {
            return abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            return Storage::disk('public')->download(
                $submission->file_path,
                $submission->file_name ?? basename($submission->file_path)
            );
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    /**
     * Format file size for display.
     *
     * @param  int  $size
     * @return string
     */
    private function formatFileSize($size)
    {
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    /**
     * Get appropriate icon class based on file extension.
     *
     * @param  string  $extension
     * @return string
     */
    private function getFileIconClass($extension)
    {
        switch (strtolower($extension)) {
            case 'pdf':
                return 'fa-file-pdf';
            case 'doc':
            case 'docx':
                return 'fa-file-word';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel';
            case 'ppt':
            case 'pptx':
                return 'fa-file-powerpoint';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fa-file-image';
            case 'zip':
            case 'rar':
                return 'fa-file-archive';
            default:
                return 'fa-file';
        }
    }

    /**
     * Get appropriate color class based on file extension.
     *
     * @param  string  $extension
     * @return string
     */
    private function getFileColorClass($extension)
    {
        switch (strtolower($extension)) {
            case 'pdf':
                return 'bg-red-500';
            case 'doc':
            case 'docx':
                return 'bg-blue-500';
            case 'xls':
            case 'xlsx':
                return 'bg-green-500';
            case 'ppt':
            case 'pptx':
                return 'bg-orange-500';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'bg-purple-500';
            case 'zip':
            case 'rar':
                return 'bg-yellow-500';
            default:
                return 'bg-gray-500';
        }
    }
}
