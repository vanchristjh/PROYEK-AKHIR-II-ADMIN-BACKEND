<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    /**
     * Get events for calendar view
     */
    public function events(Request $request)
    {
        $query = AcademicCalendar::query();
        
        // Apply date range filter
        if ($request->has('start') && $request->has('end')) {
            $query->where(function($q) use ($request) {
                $q->whereBetween('start_date', [$request->start, $request->end])
                  ->orWhereBetween('end_date', [$request->start, $request->end])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('start_date', '<=', $request->start)
                         ->where('end_date', '>=', $request->end);
                  });
            });
        }
        
        // Apply event type filter
        if ($request->has('event_types')) {
            $eventTypes = explode(',', $request->event_types);
            $query->whereIn('event_type', $eventTypes);
        }
        
        // Apply academic year filter
        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }
        
        // Apply semester filter
        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }
        
        // Apply audience filter
        if ($request->has('audience') && $request->audience) {
            $query->where(function($q) use ($request) {
                $q->where('target_audience', $request->audience)
                  ->orWhere('target_audience', 'all');
            });
        }
        
        // Get events
        $events = $query->get();
        
        return response()->json($events);
    }
    
    /**
     * Get a single event
     */
    public function event($id)
    {
        $event = AcademicCalendar::findOrFail($id);
        return response()->json($event);
    }
    
    /**
     * Get upcoming events
     */
    public function upcoming(Request $request)
    {
        $query = AcademicCalendar::query()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5);
        
        // Apply event type filter
        if ($request->has('event_types')) {
            $eventTypes = explode(',', $request->event_types);
            $query->whereIn('event_type', $eventTypes);
        }
        
        // Apply academic year filter
        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }
        
        // Apply semester filter
        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }
        
        // Apply audience filter
        if ($request->has('audience') && $request->audience) {
            $query->where(function($q) use ($request) {
                $q->where('target_audience', $request->audience)
                  ->orWhere('target_audience', 'all');
            });
        }
        
        // Get events
        $events = $query->get();
        
        return response()->json($events);
    }
}
