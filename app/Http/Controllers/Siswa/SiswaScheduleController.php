<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SiswaScheduleController extends Controller
{
    /**
     * Display the student's schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Auth::user();
        
        if (!$student->classroom_id) {
            return view('siswa.schedule.index', [
                'message' => 'Anda belum ditetapkan ke kelas manapun. Silakan hubungi administrator.'
            ]);
        }
        
        // Check if the day column exists, if not, tell the user to run migrations
        if (!Schema::hasColumn('schedules', 'day')) {
            return view('siswa.schedule.index', [
                'message' => 'Database schema perlu diperbarui. Silakan hubungi administrator.'
            ]);
        }
        
        try {
            // Get all schedules for student's classroom            // Use the utility method from the Schedule model
            $schedulesByDay = Schedule::getClassroomWeeklySchedule($student->classroom_id);
            
            // Set day names
            $dayNames = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];
            
            return view('siswa.schedule.index', [
                'schedulesByDay' => $schedulesByDay,
                'dayNames' => $dayNames
            ]);
        } catch (\Exception $e) {
            return view('siswa.schedule.index', [
                'message' => 'Terjadi kesalahan saat mengambil data jadwal. Silakan hubungi administrator: ' . $e->getMessage()
            ]);
        }    }
    
    /**
     * Export the schedule as iCalendar format
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportIcal()
    {
        $student = Auth::user();
        
        if (!$student->classroom_id) {
            return redirect()->route('siswa.schedule.index')
                ->with('error', 'Anda belum ditetapkan ke kelas manapun. Silakan hubungi administrator.');
        }
        
        try {
            // Get all schedules for student's classroom
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $student->classroom_id)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();
            
            // Start building iCal content
            $ical = "BEGIN:VCALENDAR\r\n";
            $ical .= "VERSION:2.0\r\n";
            $ical .= "PRODID:-//SMAN1//Schedule//ID\r\n";
            $ical .= "CALSCALE:GREGORIAN\r\n";
            $ical .= "METHOD:PUBLISH\r\n";
            $ical .= "X-WR-CALNAME:Jadwal Kelas {$student->classroom->name}\r\n";
            $ical .= "X-WR-CALDESC:Jadwal pelajaran untuk kelas {$student->classroom->name}\r\n";
            $ical .= "X-WR-TIMEZONE:Asia/Jakarta\r\n";
            
            // Map day number to day name
            $dayNames = [
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday'
            ];
            
            // Current week's start date (Monday)
            $today = new \DateTime();
            $dayOfWeek = $today->format('N');
            $daysToSubtract = $dayOfWeek - 1; // How many days to go back to Monday
            $monday = clone $today;
            $monday->sub(new \DateInterval("P{$daysToSubtract}D"));
            
            // Generate events for each schedule
            foreach ($schedules as $schedule) {
                $dayName = $dayNames[$schedule->day];
                
                // Clone the Monday date and add days to get to the correct day
                $eventDate = clone $monday;
                $daysToAdd = $schedule->day - 1; // 0 for Monday, 1 for Tuesday, etc.
                $eventDate->add(new \DateInterval("P{$daysToAdd}D"));
                
                // Set the times
                list($startHour, $startMinute) = explode(':', $schedule->start_time);
                list($endHour, $endMinute) = explode(':', $schedule->end_time);
                
                $dtstart = $eventDate->format('Ymd') . 'T' . sprintf('%02d%02d00', $startHour, $startMinute);
                $dtend = $eventDate->format('Ymd') . 'T' . sprintf('%02d%02d00', $endHour, $endMinute);
                
                $ical .= "BEGIN:VEVENT\r\n";
                $ical .= "UID:" . md5($schedule->id . $dtstart) . "@sman1.edu\r\n";
                $ical .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
                $ical .= "DTSTART;TZID=Asia/Jakarta:" . $dtstart . "\r\n";
                $ical .= "DTEND;TZID=Asia/Jakarta:" . $dtend . "\r\n";
                $ical .= "SUMMARY:" . $schedule->subject->name . "\r\n";
                $ical .= "DESCRIPTION:Mata Pelajaran: " . $schedule->subject->name . "\\nGuru: " . $schedule->teacher->name . "\r\n";
                $ical .= "LOCATION:" . ($schedule->room ?? 'TBD') . "\r\n";
                $ical .= "RRULE:FREQ=WEEKLY;BYDAY=" . strtoupper(substr($dayName, 0, 2)) . "\r\n";
                $ical .= "END:VEVENT\r\n";
            }
            
            $ical .= "END:VCALENDAR\r\n";
            
            // Generate filename
            $filename = 'jadwal-kelas-' . str_replace(' ', '-', $student->classroom->name) . '.ics';
            
            // Return as a downloadable file
            return response($ical)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return redirect()->route('siswa.schedule.index')
                ->with('error', 'Terjadi kesalahan saat mengekspor jadwal: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the schedule for a specific day
     *
     * @param int $day Day number (1-7)
     * @return \Illuminate\Http\Response
     */
    public function showDay($day)
    {
        $student = Auth::user();
        
        if (!$student->classroom_id) {
            return redirect()->route('siswa.schedule.index')
                ->with('error', 'Anda belum ditetapkan ke kelas manapun. Silakan hubungi administrator.');
        }
        
        if (!in_array($day, [1, 2, 3, 4, 5, 6, 7])) {
            return redirect()->route('siswa.schedule.index')
                ->with('error', 'Hari yang dipilih tidak valid.');
        }
        
        try {
            // Get schedules for this particular day
            $schedules = Schedule::with(['subject', 'teacher'])
                ->where('classroom_id', $student->classroom_id)
                ->where('day', $day)
                ->orderBy('start_time')
                ->get();
            
            // Set day name
            $dayNames = [
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu'
            ];
            
            $dayName = $dayNames[$day];
            
            return view('siswa.schedule.day', [
                'schedules' => $schedules,
                'dayName' => $dayName,
                'day' => $day,
                'classroom' => $student->classroom
            ]);
        } catch (\Exception $e) {
            return redirect()->route('siswa.schedule.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data jadwal: ' . $e->getMessage());
        }
    }
}
