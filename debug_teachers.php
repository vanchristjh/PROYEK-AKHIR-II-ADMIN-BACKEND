<?php
// DEBUG FILE: Check if teachers are being loaded correctly in the controller

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

class TeacherDebugController
{
    public function checkTeachers()
    {
        // Get teachers using the same query as in ScheduleController
        $teachers = User::where('role_id', 2)->orderBy('name')->get();
        
        // Output basic debug info
        echo "Number of teachers found: " . $teachers->count() . "<br>";
        
        // Display a table with teacher data
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>NIP</th><th>Role ID</th></tr>";
        
        foreach ($teachers as $teacher) {
            echo "<tr>";
            echo "<td>{$teacher->id}</td>";
            echo "<td>{$teacher->name}</td>";
            echo "<td>{$teacher->email}</td>";
            echo "<td>{$teacher->nip}</td>";
            echo "<td>{$teacher->role_id}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Check for potential issues in the users table
        echo "<h3>Database Analysis:</h3>";
        
        // Count users by role
        $roleStats = User::selectRaw('role_id, COUNT(*) as count')
            ->groupBy('role_id')
            ->orderBy('role_id')
            ->get();
            
        echo "<h4>Users by Role ID:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
        echo "<tr><th>Role ID</th><th>Count</th></tr>";
        
        foreach ($roleStats as $stat) {
            echo "<tr>";
            echo "<td>" . ($stat->role_id ?? 'NULL') . "</td>";
            echo "<td>{$stat->count}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Look for potential teacher records with various conditions
        echo "<h4>Users with Teacher-like attributes but wrong role_id:</h4>";
        
        $potentialTeachers = User::where(function($query) {
                $query->whereNull('role_id')
                    ->orWhere('role_id', '!=', 2);
            })
            ->where(function($query) {
                $query->whereNotNull('nip')
                    ->orWhere('email', 'like', '%guru%')
                    ->orWhere('email', 'like', '%teacher%')
                    ->orWhere('name', 'like', '%guru%')
                    ->orWhere('name', 'like', '%teacher%');
            })
            ->orderBy('id')
            ->get();
            
        if ($potentialTeachers->count() > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>NIP</th><th>Role ID</th></tr>";
            
            foreach ($potentialTeachers as $pt) {
                echo "<tr>";
                echo "<td>{$pt->id}</td>";
                echo "<td>{$pt->name}</td>";
                echo "<td>{$pt->email}</td>";
                echo "<td>{$pt->nip}</td>";
                echo "<td>" . ($pt->role_id ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            
            echo "<p style='color: red;'>There are potential teacher records with incorrect role_id values. This could be causing the teacher selection issue.</p>";
        } else {
            echo "<p>No potential teacher records found with incorrect role_id values.</p>";
        }
        
        return "";
    }
}

// Create an instance and run the check
$debugController = new TeacherDebugController();
$debugController->checkTeachers();

?>
