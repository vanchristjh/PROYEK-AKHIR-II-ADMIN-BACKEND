<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuruAnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Announcement::with('author')
            ->where(function($q) {
                // Teachers can see announcements for all or teachers, and their own announcements
                $q->whereIn('audience', ['all', 'teachers'])
                  ->orWhere('author_id', Auth::id());
            })
            ->orderBy('is_important', 'desc')
            ->orderBy('publish_date', 'desc');
        
        // Filter by importance if requested
        if ($request->has('importance') && $request->importance !== 'all') {
            $query->where('is_important', $request->importance === 'important');
        }
        
        $announcements = $query->paginate(10);
        
        return view('guru.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shared.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'audience' => 'required|in:all,students,teachers', // Teachers can only create announcements for all, students, or teachers
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
        ]);
        
        // Set author to current teacher
        $validated['author_id'] = Auth::id();
        
        // Set publish date to now if not provided
        if (empty($validated['publish_date'])) {
            $validated['publish_date'] = now();
        }
        
        // Handle attachment if provided
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }
        
        Announcement::create($validated);
        
        return redirect()->route('guru.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        // Check if teacher is allowed to see this announcement
        if (!in_array($announcement->audience, ['all', 'teachers']) && $announcement->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('shared.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        // Only allow editing of own announcements
        if ($announcement->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('shared.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Only allow updating of own announcements
        if ($announcement->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'audience' => 'required|in:all,students,teachers', // Teachers can only create announcements for all, students, or teachers
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
            'remove_attachment' => 'nullable|boolean',
        ]);
        
        // Remove attachment if requested
        if ($request->has('remove_attachment') && $announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
            $validated['attachment'] = null;
        }
        
        // Handle attachment if a new one is provided
        if ($request->hasFile('attachment')) {
            // Delete the old attachment if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $validated['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }
        
        // Remove unnecessary fields
        unset($validated['remove_attachment']);
        
        $announcement->update($validated);
        
        return redirect()->route('guru.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        // Only allow deleting of own announcements
        if ($announcement->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete attachment if exists
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }
        
        $announcement->delete();
        
        return redirect()->route('guru.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
    
    /**
     * Download the attachment for the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function download(Announcement $announcement)
    {
        // Check if teacher is allowed to access this announcement
        if (!in_array($announcement->audience, ['all', 'teachers']) && $announcement->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$announcement->attachment) {
            abort(404, 'File tidak ditemukan');
        }
        
        return Storage::disk('public')->download($announcement->attachment);
    }
}
