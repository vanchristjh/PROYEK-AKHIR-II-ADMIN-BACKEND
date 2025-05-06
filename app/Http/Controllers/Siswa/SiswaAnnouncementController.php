<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaAnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Announcement::with('author')
            ->whereIn('audience', ['all', 'students'])
            ->published() // Only show published announcements
            ->orderBy('is_important', 'desc')
            ->orderBy('publish_date', 'desc');
        
        // Filter by importance if requested
        if ($request->has('importance') && $request->importance !== 'all') {
            $query->where('is_important', $request->importance === 'important');
        }
        
        $announcements = $query->paginate(10);
        
        return view('siswa.announcements.index', compact('announcements'));
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        if ($announcement->audience !== 'all' && $announcement->audience !== 'students') {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        return view('siswa.announcements.show', compact('announcement'));
    }
}
