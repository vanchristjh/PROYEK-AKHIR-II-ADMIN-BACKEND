<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements for teachers
     */
    public function index()
    {
        $announcements = Announcement::with('author')
            ->visibleToRole('guru')
            ->active()
            ->latest('publish_date')
            ->paginate(10);
        
        return view('guru.announcements.index', compact('announcements'));
    }

    /**
     * Show form to create a new announcement (for teachers)
     */
    public function create()
    {
        return view('guru.announcements.create');
    }

    /**
     * Store a newly created announcement (for teachers)
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
        ]);
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Store the file and keep only one attachment field
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            $validated['attachment'] = $attachmentPath; // Use the existing column name
            
            // Remove the attachment_path field to prevent errors
            if (isset($validated['attachment_path'])) {
                unset($validated['attachment_path']);
            }
        }
        
        // Set publish date to now if not provided
        if (empty($validated['publish_date'])) {
            $validated['publish_date'] = now();
        }
        
        // Set is_important to false if not checked
        $validated['is_important'] = isset($validated['is_important']) ? true : false;
        
        // Set the author as current user
        $validated['author_id'] = Auth::id();
        
        Announcement::create($validated);
        
        return redirect()->route('guru.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Show details of an announcement
     */
    public function show(Announcement $announcement)
    {
        // Check if this announcement is visible to teachers
        if ($announcement->audience !== 'all' && $announcement->audience !== 'teachers') {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        return view('guru.announcements.show', compact('announcement'));
    }
    
    /**
     * Show form to edit an announcement (only author can edit)
     */
    public function edit(Announcement $announcement)
    {
        // Only the author can edit their announcements
        if ($announcement->author_id != Auth::id()) {
            abort(403, 'Anda tidak dapat mengedit pengumuman ini.');
        }
        
        return view('guru.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement (only author can update)
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Only the author can update their announcements
        if ($announcement->author_id != Auth::id()) {
            abort(403, 'Anda tidak dapat mengedit pengumuman ini.');
        }
        
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'audience' => ['required', 'string', 'in:all,teachers,students'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:publish_date'],
            'attachment' => ['nullable', 'file', 'max:10240'], // 10MB max
            'is_important' => ['sometimes', 'boolean'],
        ]);
        
        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            $validated['attachment_path'] = $attachmentPath;
        }
        
        // Set is_important to false if not checked
        $validated['is_important'] = isset($validated['is_important']) ? true : false;
        
        $announcement->update($validated);
        
        return redirect()->route('guru.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    /**
     * Download the announcement attachment
     */
    public function download(Announcement $announcement)
    {
        // Check if user has access to this announcement
        if ($announcement->audience !== 'all' && $announcement->audience !== 'teachers' && auth()->id() !== $announcement->author_id) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
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
