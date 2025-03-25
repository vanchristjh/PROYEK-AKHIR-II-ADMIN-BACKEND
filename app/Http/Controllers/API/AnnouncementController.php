<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Get announcements relevant to the authenticated user
     */
    public function getAnnouncements()
    {
        $user = Auth::user();
        $userRole = $user->role; // 'student', 'teacher', or 'admin'
        
        $query = Announcement::where('status', 'published')
            ->where(function($q) use ($userRole) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $userRole)
                  ->orWhere('target_audience', 'students_teachers');
            })
            ->whereDate('published_at', '<=', now())
            ->where(function($q) {
                $q->whereNull('expired_at')
                  ->orWhereDate('expired_at', '>=', now());
            })
            ->orderBy('priority', 'desc') // High priority first
            ->orderBy('published_at', 'desc'); // Recent first
            
        $announcements = $query->get();
        
        return response()->json([
            'success' => true,
            'announcements' => $announcements,
            'count' => $announcements->count()
        ]);
    }
}
