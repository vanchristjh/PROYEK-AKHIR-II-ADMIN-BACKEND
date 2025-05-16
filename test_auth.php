<?php
// Add this line to the top of the file to display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if Laravel is installed
if (!file_exists(__DIR__.'/vendor/autoload.php')) {
    die("Laravel not found! Make sure you're in the Laravel root directory.");
}

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "Checking authentication configuration...\n";
    
    // Test login with guru user
    $credentials = [
        'username' => 'guru',
        'password' => 'password' // Replace with actual password if different
    ];
    
    echo "Attempting to authenticate 'guru' user...\n";
    
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        echo "Authentication successful!\n";
        echo "User: {$user->name} (ID: {$user->id})\n";
        echo "Role ID: {$user->role_id}\n";
        
        $role = $user->role;
        if ($role) {
            echo "Role: {$role->name}, Slug: {$role->slug}\n";
            
            // Test role middleware
            echo "\nTesting role middleware...\n";
            $middleware = new \App\Http\Middleware\CheckRole();
            $request = \Illuminate\Http\Request::create('/guru/assignments', 'GET');
            $request->setUserResolver(function() use ($user) {
                return $user;
            });
            
            try {
                $response = $middleware->handle($request, function($req) {
                    return new \Illuminate\Http\Response("Middleware passed!");
                }, 'guru');
                
                echo "Middleware response status: " . $response->getStatusCode() . "\n";
                echo "Response content: " . $response->getContent() . "\n";
            } catch (\Exception $e) {
                echo "Middleware error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "No role found for user!\n";
        }
    } else {
        echo "Authentication failed!\n";
        
        // List available users for debugging
        echo "\nAvailable users:\n";
        $users = \App\Models\User::with('role')->get();
        foreach ($users as $user) {
            echo "- ID: {$user->id}, Name: {$user->name}, Username: " . ($user->username ?? 'NULL') . 
                 ", Password: " . (substr($user->password, 0, 20) . '...') . 
                 ", Role: " . ($user->role ? "{$user->role->name} ({$user->role->slug})" : 'NULL') . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
