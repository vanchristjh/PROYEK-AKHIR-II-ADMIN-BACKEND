<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Announcement::with('author')
                             ->published()
                             ->notExpired()
                             ->forAudience(['all', 'students']);

        // Filter by importance
        if ($request->has('importance')) {
            if ($request->importance == 'important') {
                $query->where('is_important', true);
            } elseif ($request->importance == 'regular') {
                $query->where('is_important', false);
            }
        }

        // Filter by search term
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $announcements = $query->latest('publish_date')->paginate(10);
        $importantCount = Announcement::where('is_important', true)
                              ->published()
                              ->notExpired()
                              ->forAudience(['all', 'students'])
                              ->count();

        return view('siswa.announcements.index', compact('announcements', 'importantCount'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        // Check if the announcement is visible to students
        if ($announcement->audience !== 'all' && $announcement->audience !== 'students') {
            abort(403, 'Anda tidak diizinkan untuk melihat pengumuman ini.');
        }

        // Check if the announcement is published and not expired
        if ($announcement->publish_date > now() || 
            ($announcement->expiry_date && $announcement->expiry_date < now())) {
            abort(404, 'Pengumuman tidak ditemukan.');
        }

        return view('shared.announcements.show', compact('announcement'));
    }

    /**
     * Download the announcement attachment.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function download(Announcement $announcement)
    {
        // Check if the announcement is visible to students
        if ($announcement->audience !== 'all' && $announcement->audience !== 'students') {
            abort(403, 'Anda tidak diizinkan untuk mengakses file ini.');
        }

        // Check if the announcement is published and not expired
        if ($announcement->publish_date > now() || 
            ($announcement->expiry_date && $announcement->expiry_date < now())) {
            abort(404, 'File tidak ditemukan.');
        }

        if (!$announcement->attachment || !Storage::disk('public')->exists($announcement->attachment)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($announcement->attachment, $announcement->getAttachmentName());
    }
}
