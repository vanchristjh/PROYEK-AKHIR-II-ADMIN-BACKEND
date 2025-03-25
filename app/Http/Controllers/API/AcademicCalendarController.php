<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    /**
     * Get all academic calendar events
     */
    public function getEvents(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role;
        
        $query = AcademicCalendar::where(function($q) use ($userRole) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $userRole);
            })
            ->orderBy('start_date', 'asc');
            
        // Apply optional filters
        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->has('month')) {
            $query->whereMonth('start_date', $request->month);
        }
        
        if ($request->has('year')) {
            $query->whereYear('start_date', $request->year);
        }
        
        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }
        
        $events = $query->get();
        
        return response()->json([
            'success' => true,
            'events' => $events,
            'count' => $events->count()
        ]);
    }
    
    /**
     * Get upcoming events
     */
    public function getUpcomingEvents()
    {
        $user = Auth::user();
        $userRole = $user->role;
        
        $upcomingEvents = AcademicCalendar::where(function($q) use ($userRole) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $userRole);
            })
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->take(10)
            ->get();
            
        $currentEvents = AcademicCalendar::where(function($q) use ($userRole) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', $userRole);
            })
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('end_date', 'asc')
            ->get();
            
        return response()->json([
            'success' => true,
            'upcoming_events' => $upcomingEvents,
            'current_events' => $currentEvents,
            'upcoming_count' => $upcomingEvents->count(),
            'current_count' => $currentEvents->count()
        ]);
    }
}
