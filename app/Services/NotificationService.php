<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Create a notification for a specific user
     */
    public function notifyUser(
        User $user,
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'icon_background' => $iconBackground,
            'link' => $link,
            'is_important' => $isImportant
        ]);
    }

    /**
     * Create a notification for multiple users
     */
    public function notifyUsers(
        array|Collection $users,
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        $notifications = [];
        
        foreach ($users as $user) {
            $notifications[] = $this->notifyUser(
                $user,
                $title,
                $message,
                $icon,
                $iconBackground,
                $link,
                $isImportant
            );
        }
        
        return $notifications;
    }

    /**
     * Create a notification for all users with a specific role
     */
    public function notifyByRole(
        string $role,
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        $users = User::where('role', $role)->get();
        return $this->notifyUsers($users, $title, $message, $icon, $iconBackground, $link, $isImportant);
    }

    /**
     * Create a notification for all admins
     */
    public function notifyAdmins(
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        return $this->notifyByRole('admin', $title, $message, $icon, $iconBackground, $link, $isImportant);
    }
    
    /**
     * Create a notification for all teachers
     */
    public function notifyTeachers(
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        return $this->notifyByRole('teacher', $title, $message, $icon, $iconBackground, $link, $isImportant);
    }
    
    /**
     * Create a notification for all students
     */
    public function notifyStudents(
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        return $this->notifyByRole('student', $title, $message, $icon, $iconBackground, $link, $isImportant);
    }
    
    /**
     * Create a notification for all users
     */
    public function notifyAll(
        string $title,
        string $message,
        string $icon = 'bx-bell',
        string $iconBackground = 'bg-primary-light text-primary',
        string $link = null,
        bool $isImportant = false
    ): array {
        $users = User::all();
        return $this->notifyUsers($users, $title, $message, $icon, $iconBackground, $link, $isImportant);
    }
}
