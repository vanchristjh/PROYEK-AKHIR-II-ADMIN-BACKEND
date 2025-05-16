<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth Debug - Fix Redirect Loop</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .card h2 {
            margin-top: 0;
            color: #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        pre {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Authentication Debug Tool</h1>
        <p>Use this tool to diagnose redirect loop issues in your Laravel application.</p>
        
        <div class="card">
            <h2>Authentication Status</h2>
            @if($userAuth)
                <div class="alert alert-success">
                    <strong>User is authenticated</strong>
                </div>
            @else
                <div class="alert alert-danger">
                    <strong>User is NOT authenticated</strong>
                </div>
            @endif
            
            @if($userAuth && $userData)
            <h3>User Details</h3>
            <table>
                <tr>
                    <th>User ID</th>
                    <td>{{ $userData['id'] }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $userData['name'] }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $userData['username'] }}</td>
                </tr>
                <tr>
                    <th>Role ID</th>
                    <td>{{ $userData['role_id'] }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ $userData['role'] }}</td>
                </tr>
                <tr>
                    <th>Role Slug</th>
                    <td>{{ $userData['role_slug'] }}</td>
                </tr>
            </table>
            
            <h3>Role Check Methods</h3>
            <table>
                <tr>
                    <th>isAdmin()</th>
                    <td>{{ $userData['isAdmin'] ? 'True' : 'False' }}</td>
                </tr>
                <tr>
                    <th>isGuru()</th>
                    <td>{{ $userData['isGuru'] ? 'True' : 'False' }}</td>
                </tr>
                <tr>
                    <th>isStudent()</th>
                    <td>{{ $userData['isStudent'] ? 'True' : 'False' }}</td>
                </tr>
            </table>
            @endif
        </div>
        
        <div class="card">
            <h2>Session Information</h2>
            <table>
                <tr>
                    <th>Session ID</th>
                    <td>{{ $sessionData['id'] }}</td>
                </tr>
                <tr>
                    <th>Session Cookie Name</th>
                    <td>{{ $sessionData['cookie_name'] }}</td>
                </tr>
                <tr>
                    <th>Has Session Cookie</th>
                    <td>{{ $sessionData['has_cookie'] ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Session Driver</th>
                    <td>{{ $sessionData['driver'] }}</td>
                </tr>
            </table>
            
            <h3>All Cookies</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Value</th>
                </tr>
                @foreach($cookieInfo as $cookie)
                <tr>
                    <td>{{ $cookie['name'] }}</td>
                    <td>{{ $cookie['value'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        
        <div class="card">
            <h2>Route Information</h2>
            <table>
                <tr>
                    <th>Current URI</th>
                    <td>{{ $routeInfo['current_uri'] }}</td>
                </tr>
                <tr>
                    <th>Login Route</th>
                    <td>{{ $routeInfo['login_route'] }}</td>
                </tr>
                <tr>
                    <th>Admin Dashboard Route</th>
                    <td>{{ $routeInfo['admin_dashboard'] }}</td>
                </tr>
            </table>
            
            <h3>Admin Routes Middleware</h3>
            <pre>{{ print_r($routeInfo['middleware'], true) }}</pre>
        </div>
        
        <div class="card">
            <h2>Actions to Fix Redirect Loop</h2>
            <ol>
                <li>Clear your browser cookies and cache</li>
                <li>Run <code>php artisan config:clear</code> and other cache clear commands</li>
                <li>Make sure your .env file has <code>APP_URL=http://localhost:8090</code></li>
                <li>Check that you don't have conflicting middleware redirect logic</li>
                <li>Ensure session configuration is correct</li>
            </ol>
            
            @if($userAuth)
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Log Out</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn">Go to Login</a>
            @endif
        </div>
    </div>
</body>
</html>
