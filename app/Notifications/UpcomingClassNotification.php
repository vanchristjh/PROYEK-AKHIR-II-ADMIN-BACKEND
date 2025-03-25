<?php

namespace App\Notifications;

use App\Models\ClassSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingClassNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $schedule;

    public function __construct(ClassSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $schedule = $this->schedule;
        
        return (new MailMessage)
            ->subject("Pengingat Jadwal: {$schedule->subject}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Ini adalah pengingat untuk jadwal pelajaran yang akan datang:")
            ->line("Mata Pelajaran: {$schedule->subject}")
            ->line("Waktu: {$schedule->formatted_day}, {$schedule->start_time->format('H:i')} - {$schedule->end_time->format('H:i')}")
            ->line("Ruangan: " . (isset($schedule->room) && $schedule->room ? $schedule->room : 'Reguler'))
            ->line("Guru: {$schedule->teacher->name}")
            ->action('Lihat Jadwal Lengkap', url('/schedules'))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        $schedule = $this->schedule;
        
        return [
            'schedule_id' => $schedule->id,
            'subject' => $schedule->subject,
            'day' => $schedule->day_of_week,
            'start_time' => $schedule->start_time->format('H:i'),
            'end_time' => $schedule->end_time->format('H:i'),
            'room' => $schedule->room,
            'teacher_id' => $schedule->teacher_id,
            'teacher_name' => $schedule->teacher->name,
            'class_id' => $schedule->class_id,
            'class_name' => $schedule->class->name,
        ];
    }
}
