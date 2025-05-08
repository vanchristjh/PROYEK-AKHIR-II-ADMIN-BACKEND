<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of announcements relevant to the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->input('per_page', 10);
            
            $query = Announcement::with('author')
                ->where(function($q) use ($user) {
                    // Filter by user role
                    $q->where('audience', 'all')
                      ->orWhere('audience', $user->role->slug.'s'); // administrators, teachers, students
                })
                ->orderBy('is_important', 'desc')
                ->orderBy('publish_date', 'desc');
                
            // Filter by importance if requested
            if ($request->has('importance') && $request->importance !== 'all') {
                $query->where('is_important', $request->importance === 'important');
            }
            
            $announcements = $query->paginate($perPage);
            
            return $this->success($announcements, 'Announcements retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve announcements: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Announcement $announcement)
    {
        try {
            // Check if user is allowed to view this announcement based on audience
            $user = Auth::user();
            $userRoleTargeted = ($announcement->audience === 'all' || 
                                $announcement->audience === $user->role->slug . 's');
                                
            if (!$userRoleTargeted) {
                return $this->error('You are not authorized to view this announcement', 403);
            }
            
            // Load the author relationship
            $announcement->load('author');
            
            return $this->success($announcement, 'Announcement retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve announcement: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Download the attachment for the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function download(Announcement $announcement)
    {
        try {
            // Check if attachment exists
            $attachmentPath = $announcement->getAttachmentPathAttribute();
            
            if (!$attachmentPath || !Storage::disk('public')->exists($attachmentPath)) {
                return $this->error('Attachment not found', 404);
            }
            
            // Check if user is allowed to download this attachment
            $user = Auth::user();
            $userRoleTargeted = ($announcement->audience === 'all' || 
                                $announcement->audience === $user->role->slug . 's');
                                
            if (!$userRoleTargeted) {
                return $this->error('You are not authorized to download this attachment', 403);
            }
            
            $path = Storage::disk('public')->path($attachmentPath);
            $filename = basename($attachmentPath);
            
            return response()->download($path, $filename);
        } catch (\Exception $e) {
            return $this->error('Failed to download attachment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created announcement in storage (for teachers).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'is_important' => 'boolean',
                'audience' => 'required|in:all,teachers,students',
                'attachment' => 'nullable|file|max:10240', // 10MB max
                'publish_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after_or_equal:publish_date',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $data = $request->except(['attachment']);
            
            // Set author to current user
            $data['author_id'] = Auth::id();
            
            // Set publish date to now if not provided
            if (empty($data['publish_date'])) {
                $data['publish_date'] = now();
            }
            
            // Set is_important to false if not provided
            if (!isset($data['is_important'])) {
                $data['is_important'] = false;
            }
            
            // Handle file upload if provided
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('announcements', 'public');
                $data['attachment'] = $attachmentPath;
            }
            
            $announcement = Announcement::create($data);
            
            return $this->success($announcement, 'Announcement created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create announcement: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Announcement $announcement)
    {
        try {
            // Check if user is allowed to update this announcement
            if ($announcement->author_id !== Auth::id()) {
                return $this->error('You are not authorized to update this announcement', 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'is_important' => 'boolean',
                'audience' => 'required|in:all,teachers,students',
                'attachment' => 'nullable|file|max:10240', // 10MB max
                'publish_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after_or_equal:publish_date',
                'remove_attachment' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return $this->error('Validation error', 422, $validator->errors());
            }
            
            $data = $request->except(['attachment', 'remove_attachment']);
            
            // Handle remove attachment request
            if ($request->has('remove_attachment') && $request->remove_attachment) {
                if ($announcement->attachment) {
                    Storage::disk('public')->delete($announcement->attachment);
                    $data['attachment'] = null;
                }
            }
            
            // Handle file upload if provided
            if ($request->hasFile('attachment')) {
                // Delete old file if exists
                if ($announcement->attachment) {
                    Storage::disk('public')->delete($announcement->attachment);
                }
                
                $attachmentPath = $request->file('attachment')->store('announcements', 'public');
                $data['attachment'] = $attachmentPath;
            }
            
            $announcement->update($data);
            
            return $this->success($announcement, 'Announcement updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update announcement: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Announcement $announcement)
    {
        try {
            // Check if user is allowed to delete this announcement
            if ($announcement->author_id !== Auth::id()) {
                return $this->error('You are not authorized to delete this announcement', 403);
            }
            
            // Delete attachment if exists
            if ($announcement->attachment) {
                Storage::disk('public')->delete($announcement->attachment);
            }
            
            $announcement->delete();
            
            return $this->success(null, 'Announcement deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete announcement: ' . $e->getMessage(), 500);
        }
    }
}