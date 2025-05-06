<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index()
    {
        $announcements = Announcement::with('author')
            ->latest('publish_date')
            ->paginate(10);
        
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show form to create a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'sometimes|boolean',
            'audience' => 'required|in:all,teachers,students',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'publish_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);
        
        // Set is_important to false if not checked
        $validated['is_important'] = isset($validated['is_important']) ? true : false;
        
        // Set publish date to now if not provided
        if (empty($validated['publish_date'])) {
            $validated['publish_date'] = now();
        }
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            $validated['attachment'] = $attachmentPath;
            
            // Remove attachment_path if it was accidentally set
            if (isset($validated['attachment_path'])) {
                unset($validated['attachment_path']);
            }
        }
        
        // Add author ID
        $validated['author_id'] = Auth::id();
        
        $announcement = Announcement::create($validated);
        
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Download the specified announcement attachment
     */
    public function downloadAttachment(Announcement $announcement)
    {
        if (!$announcement->attachment) {
            abort(404, 'Attachment not found');
        }
        
        $path = Storage::disk('public')->path($announcement->attachment);
        $filename = basename($announcement->attachment);
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        return response()->download($path, $filename);
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show form to edit an announcement
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students',
            'is_important' => 'sometimes|boolean',
            'publish_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'remove_attachment' => 'nullable|boolean',
        ]);
        
        // Set is_important to false if not checked
        $validated['is_important'] = isset($validated['is_important']) ? true : false;
        
        // Handle remove attachment request
        if (isset($validated['remove_attachment']) && $validated['remove_attachment']) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
                $validated['attachment'] = null;
            }
            
            unset($validated['remove_attachment']);
        }
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            $validated['attachment'] = $attachmentPath;
        }
        
        $announcement->update($validated);
        
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        // Delete attachment if exists
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }
        
        $announcement->delete();
        
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
