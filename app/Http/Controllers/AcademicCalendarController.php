<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicCalendarController extends Controller
{
    /**
     * Display a listing of the academic calendar events.
     */
    public function index(Request $request)
    {
        $eventType = $request->input('event_type');
        $month = $request->input('month');
        $year = $request->input('year', now()->year);
        $academicYear = $request->input('academic_year');
        
        $query = AcademicCalendar::query()->orderBy('start_date', 'asc');
        
        // Apply filters if provided
        if ($eventType) {
            $query->where('event_type', $eventType);
        }
        
        if ($month) {
            $query->whereMonth('start_date', $month);
        }
        
        if ($year) {
            $query->whereYear('start_date', $year);
        }
        
        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }
        
        $events = $query->get();
        
        // Group events by month for display
        $eventsByMonth = $events->groupBy(function($event) {
            return $event->start_date->format('F Y');
        });
        
        // Get event types for filter dropdown
        $eventTypes = AcademicCalendar::distinct('event_type')->pluck('event_type')->filter();
        
        // Get academic years for filter dropdown
        $academicYears = AcademicCalendar::distinct('academic_year')->pluck('academic_year')->filter();
        
        return view('dashboard.academic-calendar.index', compact(
            'events', 
            'eventsByMonth',
            'eventTypes',
            'academicYears',
            'eventType',
            'month',
            'year',
            'academicYear'
        ));
    }

    /**
     * Show the form for creating a new academic calendar event.
     */
    public function create()
    {
        return view('dashboard.academic-calendar.create');
    }

    /**
     * Store a newly created academic calendar event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'required|string',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:academic,exam,holiday,meeting,extracurricular,other',
            'is_important' => 'nullable|boolean',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|in:1,2',
            'target_audience' => 'required|in:all,students,teachers,staff',
        ]);

        // Combine date and time for start and end datetime
        $startDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
        $endDateTime = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Replace the separate fields with the combined datetime
        $data = array_merge($validated, [
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'created_by' => Auth::id(),
            'is_important' => $request->has('is_important'),
        ]);
        
        // Remove the separate time fields
        unset($data['start_time'], $data['end_time']);
        
        AcademicCalendar::create($data);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Agenda berhasil ditambahkan ke kalender akademik!');
    }

    /**
     * Display the specified academic calendar event.
     */
    public function show(AcademicCalendar $academicCalendar)
    {
        return view('dashboard.academic-calendar.show', compact('academicCalendar'));
    }

    /**
     * Show the form for editing the specified academic calendar event.
     */
    public function edit(AcademicCalendar $academicCalendar)
    {
        return view('dashboard.academic-calendar.edit', compact('academicCalendar'));
    }

    /**
     * Update the specified academic calendar event in storage.
     */
    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'required|string',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:academic,exam,holiday,meeting,extracurricular,other',
            'is_important' => 'nullable|boolean',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|in:1,2',
            'target_audience' => 'required|in:all,students,teachers,staff',
        ]);

        // Combine date and time for start and end datetime
        $startDateTime = $validated['start_date'] . ' ' . $validated['start_time'];
        $endDateTime = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Replace the separate fields with the combined datetime
        $data = array_merge($validated, [
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'is_important' => $request->has('is_important'),
        ]);
        
        // Remove the separate time fields
        unset($data['start_time'], $data['end_time']);
        
        $academicCalendar->update($data);

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Agenda kalender akademik berhasil diperbarui!');
    }

    /**
     * Remove the specified academic calendar event from storage.
     */
    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();

        return redirect()->route('academic-calendar.index')
            ->with('success', 'Agenda kalender akademik berhasil dihapus!');
    }
    
    /**
     * Display the calendar in a monthly view.
     */
    public function calendar(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        // Get the first day of the month
        $firstDay = now()->setYear($year)->setMonth($month)->startOfMonth();
        
        // Get the number of days in the month
        $daysInMonth = $firstDay->daysInMonth;
        
        // Get all events for the month
        $events = AcademicCalendar::whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orWhere(function($query) use ($year, $month) {
                $query->whereYear('end_date', $year)
                    ->whereMonth('end_date', $month);
            })
            ->orderBy('start_date')
            ->get();
        
        // Organize events by day
        $eventsByDay = [];
        
        foreach ($events as $event) {
            $start = max($event->start_date, $firstDay);
            $end = min($event->end_date, $firstDay->copy()->endOfMonth());
            
            $currentDay = $start->copy();
            
            // Add event to each day it spans
            while ($currentDay <= $end) {
                $day = $currentDay->day;
                if (!isset($eventsByDay[$day])) {
                    $eventsByDay[$day] = [];
                }
                $eventsByDay[$day][] = $event;
                $currentDay->addDay();
            }
        }
        
        return view('dashboard.academic-calendar.calendar', compact(
            'month',
            'year',
            'firstDay',
            'daysInMonth',
            'events',
            'eventsByDay'
        ));
    }
    
    /**
     * Show the upcoming events in simplified view.
     */
    public function upcoming()
    {
        $upcomingEvents = AcademicCalendar::upcoming()->take(10)->get();
        $currentEvents = AcademicCalendar::current()->get();
        
        return view('dashboard.academic-calendar.upcoming', compact('upcomingEvents', 'currentEvents'));
    }
}
