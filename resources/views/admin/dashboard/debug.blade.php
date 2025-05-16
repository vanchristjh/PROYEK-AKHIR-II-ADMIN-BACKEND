<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .debug-card {
            border-left: 5px solid #007bff;
            margin-bottom: 15px;
        }
        .debug-value {
            font-family: monospace;
            background: #f1f1f1;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .section-title {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .action-button {
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">Admin Dashboard Debug View</h1>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Success!</strong> If you can see this page, you have successfully bypassed the redirect loop.
                        </div>
                        
                        <h2 class="section-title">User Information</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card debug-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Basic Info</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Name
                                                <span class="debug-value">{{ $user->name }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Username
                                                <span class="debug-value">{{ $user->username }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Email
                                                <span class="debug-value">{{ $user->email }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                ID
                                                <span class="debug-value">{{ $user->id }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Created
                                                <span class="debug-value">{{ $user->created_at }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card debug-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Role Information</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Role Name
                                                <span class="debug-value">{{ $user->role->name ?? 'Missing' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Role Slug
                                                <span class="badge bg-primary rounded-pill">{{ $user->role->slug ?? 'Missing' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                Role ID
                                                <span class="debug-value">{{ $user->role->id ?? 'Missing' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($debug_info))
                        <h2 class="section-title mt-4">Debugging Information</h2>
                        <div class="card debug-card">
                            <div class="card-body">
                                <pre class="mb-0">{{ json_encode($debug_info, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        @endif
                        
                        <h2 class="section-title mt-4">Actions</h2>
                        <div class="d-flex flex-wrap">
                            <!-- Try Regular Admin Dashboard -->
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary action-button">
                                Try Regular Dashboard
                            </a>
                            
                            <!-- Clear Cache Button -->
                            <a href="{{ url('/clear-cache') }}" class="btn btn-outline-warning action-button">
                                Clear Laravel Cache
                            </a>
                            
                            <!-- Auth Debug Button -->
                            <a href="{{ url('/debug-auth') }}" class="btn btn-outline-info action-button">
                                Auth Debug Tool
                            </a>
                            
                            <!-- Logout Button -->
                            <a href="{{ route('logout') }}" class="btn btn-danger action-button" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log Out
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
