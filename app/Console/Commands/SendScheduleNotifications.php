<?php

namespace App\Console\Commands;

use App\Models\ClassSchedule;
use App\Models\ScheduleNotification;
use App\Notifications\UpcomingClassNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendScheduleNotifications extends Command
{
    protected $signature = 'schedules:notify';
    protected $description = 'Send notifications for upcoming class schedules';

    public function handle()
    {
        $this->info('Checking for upcoming schedules...');
        $schedules = ClassSchedule::forNotification()->get();
        $count = 0;

        foreach ($schedules as $schedule) {
            if ($schedule->shouldSendNotification()) {
                $this->sendNotification($schedule);
                $count++;
                
                // Update the last notification time
                $schedule->update(['last_notification_sent' => now()]);
            }
        }

        $this->info("Sent notifications for {$count} schedules.");
        return Command::SUCCESS;
    }

    protected function sendNotification(ClassSchedule $schedule)
    {
        try {
            $this->info("Sending notification for {$schedule->subject} class");
            
            // Get all students in the class
            $students = $schedule->students;
            $teacher = $schedule->teacher;
            
            // Create notification title and message
            $title = "Jadwal: {$schedule->subject}";
            $message = "Pelajaran {$schedule->subject} akan dimulai pada pukul {$schedule->start_time->format('H:i')} di ruangan " . ($schedule->room ? $schedule->room : 'reguler') . ".";
            
            // Create the notification record
            $notificationTime = now();
            
            // Notify the teacher
            if ($teacher && $schedule->notify_by_email) {
                $teacher->notify(new UpcomingClassNotification($schedule));
                
                ScheduleNotification::create([
                    'class_schedule_id' => $schedule->id,
                    'user_id' => $teacher->id,
                    'title' => $title,
                    'message' => $message,
                    'notification_time' => $notificationTime,
                    'sent_at' => now(),
                    'is_read' => false,
                    'type' => 'teacher',
                ]);
            }
            
            // Notify all students
            foreach ($students as $student) {
                if ($schedule->notify_by_email) {
                    $student->notify(new UpcomingClassNotification($schedule));
                }
                
                ScheduleNotification::create([
                    'class_schedule_id' => $schedule->id,
                    'user_id' => $student->id,
                    'title' => $title,
                    'message' => $message,
                    'notification_time' => $notificationTime,
                    'sent_at' => now(),
                    'is_read' => false,
                    'type' => 'student',
                ]);
            }
            
            // For push notifications, we would integrate with a service like Firebase
            // This would be implemented based on the specific push notification system in use
            
            $this->info("Notification sent successfully for {$schedule->subject}");
        } catch (\Exception $e) {
            Log::error("Error sending notification for schedule {$schedule->id}: " . $e->getMessage());
            $this->error("Error sending notification: " . $e->getMessage());
        }
    }
}
