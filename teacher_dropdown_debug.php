<?php
// Teacher dropdown debug tool

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dropdown Debug</title>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .section { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .select-container { margin-top: 20px; }
        select { padding: 8px; width: 300px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Teacher Dropdown Debug</h1>
        
        <div class='section'>
            <h2>Database Query Results</h2>";

// Get teachers from database
$teachers = User::where('role_id', 2)->orderBy('name')->get();
echo "<p>Teachers with role_id=2: " . $teachers->count() . "</p>";

echo "<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role ID</th>
    </tr>";

foreach ($teachers as $teacher) {
    echo "<tr>
        <td>{$teacher->id}</td>
        <td>{$teacher->name}</td>
        <td>{$teacher->email}</td>
        <td>{$teacher->role_id}</td>
    </tr>";
}
echo "</table>";

echo "</div>

<div class='section'>
    <h2>Teacher Dropdown Test</h2>
    <p>This is how the teacher dropdown should appear in the schedule form:</p>
    
    <div class='select-container'>
        <select id='teacher_id'>
            <option value=''>-- Pilih Guru --</option>";
            
foreach ($teachers as $teacher) {
    echo "<option value='{$teacher->id}'>{$teacher->name}</option>";
}
            
echo "</select>
    </div>
</div>

<div class='section'>
    <h2>JavaScript Initialization Test</h2>
    <p>This section tests how the JavaScript initializes teacher options:</p>
    
    <div id='js-test-results'>Running test...</div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const teacherSelect = document.getElementById('teacher_id');
            const testResultsDiv = document.getElementById('js-test-results');
            
            // Test the teacher select initialization
            const originalTeacherOptions = [];
            Array.from(teacherSelect.options).forEach(option => {
                if (option.value) { // Skip the placeholder option
                    originalTeacherOptions.push({
                        id: option.value,
                        name: option.textContent
                    });
                }
            });
            
            // Create test results
            let resultsHTML = `<p>Found ${originalTeacherOptions.length} teacher options in the select element</p>`;
            
            if (originalTeacherOptions.length > 0) {
                resultsHTML += `<p>✅ Teacher options are present and should work correctly.</p>`;
                resultsHTML += `<p>Teacher options:</p><ul>`;
                originalTeacherOptions.forEach(teacher => {
                    resultsHTML += `<li>ID: ${teacher.id}, Name: ${teacher.name}</li>`;
                });
                resultsHTML += `</ul>`;
            } else {
                resultsHTML += `<p>❌ No teacher options found. This is the likely cause of the empty dropdown issue.</p>`;
                resultsHTML += `<p>Number of options in select: ${teacherSelect.options.length}</p>`;
                resultsHTML += `<p>First option value: ${teacherSelect.options[0]?.value || 'N/A'}</p>`;
                resultsHTML += `<p>First option text: ${teacherSelect.options[0]?.textContent || 'N/A'}</p>`;
            }
            
            testResultsDiv.innerHTML = resultsHTML;
        });
    </script>
</div>

<div class='section'>
    <h2>Fix Instructions</h2>
    <p>Based on the tests above, here's how to fix the issue:</p>
    
    <div id='fix-instructions'>
        <p><strong>If no teacher options appear:</strong></p>
        <ol>
            <li>Make sure teachers exist in the database with role_id=2</li>
            <li>Check the controller to ensure it's passing teachers to the view</li>
            <li>Verify the Blade template properly loops through teachers</li>
            <li>Try the 'Refresh' button on the schedule creation page</li>
            <li>Clear Laravel cache (php artisan cache:clear)</li>
        </ol>
        
        <p><strong>Quick Fix (if needed):</strong></p>
        <ol>
            <li>For administrators:</li>
            <code>php fix_teacher_roles.php</code> to fix any incorrect role IDs
            <li>For developers:</li>
            <ul>
                <li>Check SQL queries for role_id=2 users</li>
                <li>Verify that the JavaScript is correctly handling the options</li>
                <li>Add a direct refresh button (added via JavaScript)</li>
            </ul>
        </ol>
    </div>
</div>

</div>
</body>
</html>";
