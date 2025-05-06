<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class AdminAnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Announcement::with('author')->orderBy('is_important', 'desc')->orderBy('publish_date', 'desc');
        
        // Filter by importance if requested
        if ($request->has('importance') && $request->importance !== 'all') {
            $query->where('is_important', $request->importance === 'important');
        }
        
        // Filter by author if requested
        if ($request->has('author') && !empty($request->author)) {
            $query->where('author_id', $request->author);
        }
        
        $announcements = $query->paginate(10);
        $authors = User::whereHas('announcements')->get();
        
        return view('admin.announcements.index', compact('announcements', 'authors'));
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
            'audience' => 'required|in:all,administrators,teachers,students',
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
        ]);
        
        // Set author to current admin
        $validated['author_id'] = Auth::id();
        
        // Set publish date to now if not provided
        if (empty($validated['publish_date'])) {
            $validated['publish_date'] = now();
        }
        
        // Handle attachment if provided
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            
            // Check which attachment column to use
            if (Schema::hasColumn('announcements', 'attachment')) {
                $validated['attachment'] = $attachmentPath;
            } else if (Schema::hasColumn('announcements', 'attachment_path')) {
                $validated['attachment_path'] = $attachmentPath;
            }
        }
        
        Announcement::create($validated);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'audience' => 'required|in:all,administrators,teachers,students',
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
            'remove_attachment' => 'nullable|boolean',
        ]);
        
        // Remove attachment if requested
        if ($request->has('remove_attachment') && $announcement->getAttachmentAttribute()) {
            Storage::disk('public')->delete($announcement->getAttachmentAttribute());
            
            // Determine which column to nullify
            if (Schema::hasColumn('announcements', 'attachment')) {
                $validated['attachment'] = null;
            } else if (Schema::hasColumn('announcements', 'attachment_path')) {
                $validated['attachment_path'] = null;
            }
        }
        
        // Handle attachment if a new one is provided
        if ($request->hasFile('attachment')) {
            // Delete the old attachment if exists
            if ($announcement->getAttachmentAttribute()) {
                Storage::disk('public')->delete($announcement->getAttachmentAttribute());
            }
            
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            
            // Check which attachment column to use
            if (Schema::hasColumn('announcements', 'attachment')) {
                $validated['attachment'] = $attachmentPath;
            } else if (Schema::hasColumn('announcements', 'attachment_path')) {
                $validated['attachment_path'] = $attachmentPath;
            }
        }
        
        // Remove unnecessary fields
        unset($validated['remove_attachment']);
        
        $announcement->update($validated);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        // Delete attachment if exists
        if ($announcement->getAttachmentAttribute()) {
            Storage::disk('public')->delete($announcement->getAttachmentAttribute());
        }
        
        $announcement->delete();
        
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
    
    /**
     * Download the attachment for the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function download(Announcement $announcement)
    {
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
