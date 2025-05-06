<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements for students.
     */
    public function index()
    {
        // Get only published announcements visible to students
        $announcements = Announcement::where('publish_date', '<=', now())
            ->where(function($query) {
                $query->where('audience', 'all')
                      ->orWhere('audience', 'students');
            })
            ->orderBy('is_important', 'desc')
            ->orderBy('publish_date', 'desc')
            ->paginate(10);
            
        return view('siswa.announcements.index', compact('announcements'));
    }
    
    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        // Check if student has access to this announcement
        if (!($announcement->audience === 'all' || $announcement->audience === 'students')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengumuman ini.');
        }
        
        // Check if announcement is published
        if ($announcement->publish_date > now()) {
            abort(403, 'Pengumuman ini belum dipublikasikan.');
        }
        
        return view('siswa.announcements.show', compact('announcement'));
    }
    
    /**
     * Download the announcement attachment
     */
    public function download(Announcement $announcement)
    {
        // Check if student has access to this announcement
        if (!($announcement->audience === 'all' || $announcement->audience === 'students')) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh lampiran pengumuman ini.');
        }
        
        // Check if announcement is published
        if ($announcement->publish_date > now()) {
            abort(403, 'Pengumuman ini belum dipublikasikan.');
        }
        
        // Check if attachment exists
        if (!$announcement->attachment || !Storage::disk('public')->exists($announcement->attachment)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        
        // Get the file path
        $path = storage_path('app/public/' . $announcement->attachment);
        
        // Get the original file name
        $filename = basename($announcement->attachment);
        
        // Return the file as a download
        return response()->download($path, $filename);
    }
}
