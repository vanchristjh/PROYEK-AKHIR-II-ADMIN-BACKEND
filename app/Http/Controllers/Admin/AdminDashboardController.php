<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Activity;
use App\Traits\LogsActivity;
use App\Helpers\ActivityRenderer;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    use LogsActivity;
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        try {
            // Get statistics for admin dashboard
            $studentCount = User::whereHas('role', function ($query) {
                $query->where('slug', 'siswa');
            })->count();

            $teacherCount = User::whereHas('role', function ($query) {
                $query->where('slug', 'guru');
            })->count();

            $classroomCount = Classroom::count();
            $subjectCount = Subject::count();

            // Get recent users
            $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

            // Get recent classrooms with student count
            $recentClassrooms = Classroom::selectRaw('classrooms.*, (SELECT COUNT(*) FROM users WHERE classrooms.id = users.classroom_id AND role_id = 3) as students_count')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();        // Get subjects with teacher count
        $recentSubjects = Subject::selectRaw('subjects.*, (SELECT COUNT(*) FROM users INNER JOIN subject_teacher ON users.id = subject_teacher.teacher_id WHERE subjects.id = subject_teacher.subject_id AND EXISTS (SELECT * FROM roles WHERE users.role_id = roles.id AND slug = "guru")) as teachers_count')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

            // Get recent teachers for the table display
            $recentTeachers = User::whereHas('role', function ($query) {
                $query->where('slug', 'guru');
            })->with(['teacherSubjects'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Get recent students for the table display
            $recentStudents = User::whereHas('role', function ($query) {
                $query->where('slug', 'siswa');
            })->with(['classroom'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Get recent announcements
            $recentAnnouncements = Announcement::with('author')
                ->orderBy('publish_date', 'desc')
                ->take(5)
                ->get()
                ->map(function ($announcement) {
                    $announcement->formatted_date = $announcement->publish_date->format('d M Y');
                    $announcement->excerpt = \Str::limit($announcement->content, 100);
                    return $announcement;
                });

            // Get recent activities
            $recentActivities = $this->getRecentActivities(5);

            // Define colors for activity icons
            $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'orange', 'teal'];

            return view('dashboard.admin', compact(
                'studentCount',
                'teacherCount',
                'classroomCount',
                'subjectCount',
                'recentUsers',
                'recentClassrooms',
                'recentSubjects',
                'recentTeachers',
                'recentStudents',
                'recentAnnouncements',
                'recentActivities',
                'colors'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for the admin dashboard.
     */
    public function getStats()
    {
        // Get statistics
        $studentCount = User::whereHas('role', function ($query) {
            $query->where('slug', 'siswa');
        })->count();

        $teacherCount = User::whereHas('role', function ($query) {
            $query->where('slug', 'guru');
        })->count();

        $classroomCount = Classroom::count();
        $subjectCount = Subject::count();

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get()->map(function ($user) {
            // Format the user data for the frontend
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'slug' => $user->role->slug
                ]
            ];
        });

        // Get recent classrooms with student count
        $recentClassrooms = Classroom::selectRaw('classrooms.*, (SELECT COUNT(*) FROM users WHERE classrooms.id = users.classroom_id AND role_id = 3) as students_count')
            ->with('homeroomTeacher')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($classroom) {
                return [
                    'id' => $classroom->id,
                    'name' => $classroom->name,
                    'students_count' => $classroom->students_count,
                    'homeroom_teacher' => $classroom->homeroomTeacher ? [
                        'id' => $classroom->homeroomTeacher->id,
                        'name' => $classroom->homeroomTeacher->name
                    ] : null
                ];
            });        // Get subjects with teacher count
        $recentSubjects = Subject::selectRaw('subjects.*, (SELECT COUNT(*) FROM users INNER JOIN subject_teacher ON users.id = subject_teacher.teacher_id WHERE subjects.id = subject_teacher.subject_id AND EXISTS (SELECT * FROM roles WHERE users.role_id = roles.id AND slug = "guru")) as teachers_count')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'teachers_count' => $subject->teachers_count
                ];
            });

        // Get recent announcements
        $recentAnnouncements = Announcement::with('author')
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,
                    'excerpt' => \Str::limit($announcement->content, 100),
                    'publish_date' => $announcement->publish_date,
                    'formatted_date' => $announcement->publish_date->format('d M Y'),
                    'is_important' => $announcement->is_important,
                    'author_id' => $announcement->author_id,
                    'author' => $announcement->author ? [
                        'id' => $announcement->author->id,
                        'name' => $announcement->author->name
                    ] : null
                ];
            });        // Log the data refresh
        $this->logActivity('Dashboard Refresh', 'Admin refreshed dashboard data', 'refresh');
        
        // Get recent activities
        $activities = $this->getRecentActivities(5)->map(function($activity) {
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'type' => $activity->type,
                'created_at' => $activity->created_at->format('d M Y, H:i'),
                'user' => $activity->user ? [
                    'id' => $activity->user->id,
                    'name' => $activity->user->name
                ] : null
            ];
        });

        // Return JSON response with updated data
        return response()->json([
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classroomCount' => $classroomCount,
            'subjectCount' => $subjectCount,
            'recentUsers' => $recentUsers,
            'recentClassrooms' => $recentClassrooms,
            'recentSubjects' => $recentSubjects,
            'recentAnnouncements' => $recentAnnouncements,
            'recentActivities' => $activities
        ]);
    }

    /**
     * Get only the recent activities (for specific activity refresh)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivities()
    {
        // Log the activity refresh
        $this->logActivity('Activity Refresh', 'Admin refreshed activity log', 'refresh');
        
        // Get recent activities
        $activities = $this->getRecentActivities(10)->map(function($activity) {
            return [
                'id' => $activity->id,
                'description' => $activity->description,
                'type' => $activity->type,
                'created_at' => $activity->created_at->format('d M Y, H:i'),
                'user' => $activity->user ? [
                    'id' => $activity->user->id,
                    'name' => $activity->user->name
                ] : null
            ];
        });

        // Return JSON response with only activity data
        return response()->json([
            'recentActivities' => $activities
        ]);
    }    /**
     * Get a rendered activity item template.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getActivityTemplate(Request $request)
    {
        // Create an activity object from the request parameters
        $activity = new \stdClass();
        $activity->id = $request->input('id');
        $activity->type = $request->input('type', 'system');
        $activity->description = $request->input('description');
        $activity->created_at = \Carbon\Carbon::parse($request->input('created_at', now()));
        
        // If there's a user ID, fetch the user
        if ($request->input('user_id')) {
            $activity->user = User::find($request->input('user_id'));
        }
        
        // Use our ActivityRenderer to generate the HTML
        return ActivityRenderer::render($activity, 0);
    }
}
