<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $query = Announcement::with('author');

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
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // Teachers should see all announcements for teachers and all audiences
        $query->where(function ($q) {
            $q->where('audience', 'all')
              ->orWhere('audience', 'teachers');
        });

        $announcements = $query->latest('publish_date')->paginate(10);

        return view('guru.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('guru.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students',
            'is_important' => 'sometimes|boolean',
            'publish_date' => 'required|date',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif'
        ]);

        // Teachers cannot create announcements for administrators
        if ($validatedData['audience'] == 'administrators') {
            $validatedData['audience'] = 'teachers';
        }

        $validatedData['author_id'] = Auth::id();
        $validatedData['is_important'] = $request->has('is_important'); // Convert checkbox to boolean
        
        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validatedData['attachment'] = $request->file('attachment')
                ->store('announcements', 'public');
        }

        Announcement::create($validatedData);

        return redirect()->route('guru.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return view('guru.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        // Teachers can only edit their own announcements
        if ($announcement->author_id !== Auth::id()) {
            return redirect()->route('guru.announcements.index')
                ->with('error', 'Anda tidak diizinkan untuk mengedit pengumuman ini.');
        }

        return view('guru.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Teachers can only update their own announcements
        if ($announcement->author_id !== Auth::id()) {
            return redirect()->route('guru.announcements.index')
                ->with('error', 'Anda tidak diizinkan untuk mengubah pengumuman ini.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:all,teachers,students',
            'is_important' => 'sometimes|boolean',
            'publish_date' => 'required|date',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif',
            'remove_attachment' => 'sometimes|boolean'
        ]);

        // Set is_important to false if not provided
        $validatedData['is_important'] = $request->has('is_important');

        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment && Storage::disk('public')->exists($announcement->attachment)) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            $validatedData['attachment'] = $request->file('attachment')
                ->store('announcements', 'public');
        } elseif ($request->has('remove_attachment') && $announcement->attachment) {
            // Remove attachment if requested
            Storage::disk('public')->delete($announcement->attachment);
            $validatedData['attachment'] = null;
        } else {
            // Keep the existing attachment
            unset($validatedData['attachment']);
        }

        $announcement->update($validatedData);

        return redirect()->route('guru.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        // Teachers can only delete their own announcements
        if ($announcement->author_id !== Auth::id()) {
            return redirect()->route('guru.announcements.index')
                ->with('error', 'Anda tidak diizinkan untuk menghapus pengumuman ini.');
        }

        // Delete attachment if exists
        if ($announcement->attachment && Storage::disk('public')->exists($announcement->attachment)) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('guru.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Download the announcement attachment.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function download(Announcement $announcement)
    {
        if (!$announcement->attachment || !Storage::disk('public')->exists($announcement->attachment)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($announcement->attachment, $announcement->getAttachmentName());
    }
}
