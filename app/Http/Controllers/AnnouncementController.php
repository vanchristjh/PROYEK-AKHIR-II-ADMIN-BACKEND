<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Announcement::with('author')
            ->where(function($q) use ($user) {
                // Filter by user role (admin sees all, guru and siswa see announcements for their roles)
                if (!$user->isAdmin()) {
                    $q->whereIn('audience', ['all', $user->role->slug.'s']); // administrators, teachers, students
                }
            })
            ->orderBy('is_important', 'desc')
            ->orderBy('publish_date', 'desc');
            
        // Filter by importance if requested
        if ($request->has('importance') && $request->importance !== 'all') {
            $query->where('is_important', $request->importance === 'important');
        }
        
        // Filter by author if requested and user is admin
        if ($user->isAdmin() && $request->has('author') && !empty($request->author)) {
            $query->where('author_id', $request->author);
        }
        
        $announcements = $query->paginate(10);
        
        // Get authors for filter if user is admin
        $authors = $user->isAdmin() ? User::whereHas('authoredAnnouncements')->get() : null;
        
        $viewPrefix = $this->getViewPrefix();
        return view($viewPrefix . '.announcements.index', compact('announcements', 'authors'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        // Only admin and guru can create announcements
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isGuru()) {
            return redirect()->route('unauthorized');
        }
        
        $viewPrefix = $this->getViewPrefix();
        return view($viewPrefix . '.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        // Only admin and guru can create announcements
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isGuru()) {
            return redirect()->route('unauthorized');
        }
        
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'audience' => 'required|in:all,administrators,teachers,students',
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:publish_date',
        ]);
        
        $data = $request->except(['attachment']);
        
        // Set author to current user
        $data['author_id'] = Auth::id();
        
        // Set publish date to now if not provided
        if (empty($data['publish_date'])) {
            $data['publish_date'] = now();
        }
        
        // Set is_important to false if not checked
        $data['is_important'] = isset($data['is_important']) ? (bool)$data['is_important'] : false;
        
        // Handle file upload if provided
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            
            // Check if the table has 'attachment' or 'attachment_path' column
            if (Schema::hasColumn('announcements', 'attachment')) {
                $data['attachment'] = $attachmentPath;
            } else if (Schema::hasColumn('announcements', 'attachment_path')) {
                $data['attachment_path'] = $attachmentPath;
            }
        }
        
        $announcement = Announcement::create($data);
        
        $routePrefix = $this->getRoutePrefix();
        return redirect()->route($routePrefix . '.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        // Check if user is allowed to view this announcement based on audience
        $user = Auth::user();
        $userRoleTargeted = ($announcement->audience === 'all' || 
                            $announcement->audience === $user->role->slug . 's');
                            
        if (!$user->isAdmin() && !$userRoleTargeted) {
            return redirect()->route('unauthorized');
        }
        
        $viewPrefix = $this->getViewPrefix();
        return view($viewPrefix . '.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        // Check if user is allowed to edit this announcement
        $user = Auth::user();
        if (!$user->isAdmin() && $announcement->author_id !== $user->id) {
            return redirect()->route('unauthorized');
        }
        
        $viewPrefix = $this->getViewPrefix();
        return view($viewPrefix . '.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Check if user is allowed to update this announcement
        $user = Auth::user();
        if (!$user->isAdmin() && $announcement->author_id !== $user->id) {
            return redirect()->route('unauthorized');
        }
        
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'boolean',
            'audience' => 'required|in:all,administrators,teachers,students',
            'attachment' => 'nullable|file|max:10240',
            'publish_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:publish_date',
            'remove_attachment' => 'nullable|boolean',
        ]);
        
        $data = $request->except(['attachment', 'remove_attachment']);
        
        // Set is_important to false if not checked
        $data['is_important'] = isset($data['is_important']) ? (bool)$data['is_important'] : false;
        
        // Handle remove attachment request
        if ($request->has('remove_attachment') && $request->remove_attachment) {
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
                $data['attachment'] = null;
                
                // Also check for attachment_path field
                if (Schema::hasColumn('announcements', 'attachment_path')) {
                    $data['attachment_path'] = null;
                }
            }
        }
        
        // Handle file upload if provided
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
            
            // Check which field to use for attachment
            if (Schema::hasColumn('announcements', 'attachment')) {
                $data['attachment'] = $attachmentPath;
            } else if (Schema::hasColumn('announcements', 'attachment_path')) {
                $data['attachment_path'] = $attachmentPath;
            }
        }
        
        $announcement->update($data);
        
        $routePrefix = $this->getRoutePrefix();
        return redirect()->route($routePrefix . '.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        // Check if user is allowed to delete this announcement
        $user = Auth::user();
        if (!$user->isAdmin() && $announcement->author_id !== $user->id) {
            return redirect()->route('unauthorized');
        }
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Delete attachment if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            // Delete the announcement
            $announcement->delete();
            
            // Commit transaction
            DB::commit();
            
            $routePrefix = $this->getRoutePrefix();
            return redirect()->route($routePrefix . '.announcements.index')
                ->with('success', 'Pengumuman berhasil dihapus.');
                
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            // Log error
            \Log::error('Error deleting announcement: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus pengumuman: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Download the attachment for the specified announcement
     */
    public function downloadAttachment(Announcement $announcement)
    {
        // Check if attachment exists
        $attachmentPath = $announcement->getAttachmentPathAttribute();
        
        if (!$attachmentPath) {
            abort(404, 'Attachment not found');
        }
        
        // Check if user is allowed to download this attachment
        $user = Auth::user();
        $userRoleTargeted = ($announcement->audience === 'all' || 
                            $announcement->audience === $user->role->slug . 's');
                            
        if (!$user->isAdmin() && !$userRoleTargeted) {
            return redirect()->route('unauthorized');
        }
        
        $path = Storage::disk('public')->path($attachmentPath);
        $filename = Str::afterLast($attachmentPath, '/');
        
        return response()->download($path, $filename);
    }
    
    /**
     * Download the attachment for the specified announcement
     */
    public function download(Announcement $announcement)
    {
        // Check if attachment exists
        if (!$announcement->attachment) {
            abort(404, 'File tidak ditemukan');
        }
        
        // Check if user is allowed to download this attachment
        $user = Auth::user();
        $userRoleTargeted = ($announcement->audience === 'all' || 
                            $announcement->audience === $user->role->slug . 's');
                            
        if (!$user->isAdmin() && !$userRoleTargeted && $announcement->author_id !== $user->id) {
            return redirect()->route('unauthorized');
        }
        
        return Storage::disk('public')->download($announcement->attachment, basename($announcement->attachment));
    }
    
    /**
     * Get the view prefix based on user role
     */
    private function getViewPrefix()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return 'admin';
        } elseif ($user->isGuru()) {
            return 'guru';
        } else {
            return 'siswa';
        }
    }
    
    /**
     * Get the route prefix based on user role
     */
    private function getRoutePrefix()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return 'admin';
        } elseif ($user->isGuru()) {
            return 'guru';
        } else {
            return 'siswa';
        }
    }
}
