<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('dashboard.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('dashboard.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after_or_equal:published_at',
            'target_audience' => 'required|in:all,students,teachers,staff',
            'priority' => 'required|in:high,medium,low',
        ]);

        // Set default published_at to now if status is published and no date is provided
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Add the user who created this announcement
        $validated['created_by'] = Auth::id();

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return view('dashboard.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('dashboard.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,draft,archived',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date|after_or_equal:published_at',
            'target_audience' => 'required|in:all,students,teachers,staff',
            'priority' => 'required|in:high,medium,low',
        ]);

        // Set default published_at to now if status is published and no date is provided
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
    
    /**
     * Display a listing of active announcements for users.
     */
    public function list()
    {
        // Get all announcements for debugging
        $allAnnouncements = Announcement::orderBy('created_at', 'desc')->get();
        
        // Apply the active scope for filtering
        $activeAnnouncements = Announcement::active()
            ->orderBy('priority', 'asc')
            ->orderBy('published_at', 'desc')
            ->get();
        
        // Check if we have announcements
        $announcementCount = $allAnnouncements->count();
        $activeCount = $activeAnnouncements->count();
        
        // Return view with both active announcements and debug info
        return view('dashboard.announcements.list', compact(
            'activeAnnouncements',
            'allAnnouncements',
            'announcementCount',
            'activeCount'
        ));
    }
    
    /**
     * Change the status of an announcement.
     */
    public function changeStatus(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,draft,archived',
        ]);
        
        // If changing to published and no published_at date
        if ($validated['status'] === 'published' && !$announcement->published_at) {
            $announcement->published_at = now();
        }
        
        $announcement->status = $validated['status'];
        $announcement->save();
        
        return redirect()->back()
            ->with('success', 'Status pengumuman berhasil diubah!');
    }
}
