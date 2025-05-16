<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Update submission score and feedback
     */
    public function update(Request $request, AssignmentSubmission $submission)
    {
        // Check if the submission's assignment belongs to the authenticated teacher
        if ($submission->assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'score' => 'nullable|numeric|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string'
        ]);
        
        $submission->score = $validated['score'] ?? null;
        $submission->feedback = $validated['feedback'] ?? null;
        $submission->save();
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }
    
    /**
     * Download the submission file
     */
    public function download(AssignmentSubmission $submission)
    {
        // Check if the submission's assignment belongs to the authenticated teacher
        if ($submission->assignment->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$submission->file_path) {
            abort(404, 'File not found.');
        }
        
        return response()->download(storage_path('app/public/' . $submission->file_path), $submission->file_name);
    }
}
