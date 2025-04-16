<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(10);
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
        $validated = $this->validateAnnouncement($request);
        
        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $this->storeAttachment($request);
        }
        
        $validated['created_by'] = auth()->id();
        
        Announcement::create($validated);
        
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat');
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
        $validated = $this->validateAnnouncement($request, $announcement->id);
        
        // Handle attachment: new upload, keep existing, or remove
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            
            $validated['attachment_path'] = $this->storeAttachment($request);
        } elseif ($request->has('remove_attachment') && $request->remove_attachment) {
            // Remove existing attachment
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            $validated['attachment_path'] = null;
        } else {
            // Keep existing attachment
            unset($validated['attachment_path']);
        }
        
        $announcement->update($validated);
        
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete attachment if exists
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }
        
        $announcement->delete();
        
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus');
    }
    
    /**
     * Display a listing of active announcements for users.
     */
    public function list()
    {
        // Get active announcements
        $activeAnnouncements = Announcement::active()
            ->orderBy('priority', 'desc')
            ->orderBy('published_at', 'desc')
            ->get();
        
        // For admins, also get all announcements for debugging
        $allAnnouncements = null;
        $announcementCount = 0;
        $activeCount = 0;
        
        if (auth()->check() && auth()->user()->role === 'admin') {
            $allAnnouncements = Announcement::orderBy('created_at', 'desc')->limit(30)->get();
            $announcementCount = Announcement::count();
            $activeCount = Announcement::active()->count();
        }
        
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
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);
        
        $announcement->status = $request->status;
        $announcement->save();
        
        return redirect()->back()->with('success', 'Status pengumuman berhasil diubah');
    }

    /**
     * Mark an announcement as read.
     */
    public function markAsRead(Announcement $announcement)
    {
        // Get current read announcements from session
        $readAnnouncements = session('read_announcements', []);
        
        // Add this announcement ID if not already in the list
        if (!in_array($announcement->id, $readAnnouncements)) {
            $readAnnouncements[] = $announcement->id;
        }
        
        // Store updated list back to session
        session(['read_announcements' => $readAnnouncements]);
        
        return redirect()->back()->with('success', 'Pengumuman ditandai sebagai sudah dibaca');
    }

    /**
     * Download the attachment of an announcement.
     */
    public function downloadAttachment(Announcement $announcement)
    {
        if (!$announcement->attachment_path) {
            abort(404, 'Lampiran tidak ditemukan');
        }
        
        return Storage::disk('public')->download($announcement->attachment_path);
    }

    /**
     * Validate announcement data.
     */
    private function validateAnnouncement(Request $request, $id = null)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'expired_at' => 'nullable|date',
            'target_audience' => 'required|in:all,students,teachers,staff',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'
        ]);
    }

    /**
     * Store attachment file.
     */
    private function storeAttachment(Request $request)
    {
        return $request->file('attachment')->store('announcement-attachments', 'public');
    }
}
