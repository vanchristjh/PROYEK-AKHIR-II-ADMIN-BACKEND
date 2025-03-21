@extends('layouts.app')

@section('title', 'API Documentation')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">API Documentation</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">API Documentation</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-book me-1"></i>
                        API Integration Guide
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="h4 mb-3">Overview</h2>
                    <p>
                        This API allows integration with the SMA Mobile App to access student and teacher accounts.
                        All API endpoints (except login) require authentication using Laravel Sanctum tokens.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Base URL for all API requests: <code>{{ url('/api') }}</code>
                    </div>
                    
                    <!-- Authentication Endpoints -->
                    <h2 class="h4 mt-4 mb-3 border-bottom pb-2">Authentication</h2>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-primary me-2">POST</span> /api/login</h3>
                        <p>Authenticates a user and returns a token for API access.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Request Body
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "email": "student@example.com",
    "password": "password",
    "device_name": "Flutter Mobile App"
}</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "user": {
        "id": 1,
        "name": "Student Name",
        "email": "student@example.com",
        "role": "student",
        "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
        // other user attributes
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "message": "Login berhasil"
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-primary me-2">POST</span> /api/logout</h3>
                        <p>Logs out a user and invalidates their token.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "message": "Logout berhasil"
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Student Endpoints -->
                    <h2 class="h4 mt-5 mb-3 border-bottom pb-2">Student Endpoints</h2>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-success me-2">GET</span> /api/students</h3>
                        <p>Retrieves student data. For student users, returns only their own data.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "students": [
        {
            "id": 1,
            "name": "Student Name",
            "email": "student@example.com",
            "role": "student",
            "nis": "123456",
            "nisn": "123456789",
            "class": "XI-A",
            "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
            // other student attributes
        }
    ],
    "count": 1
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-success me-2">GET</span> /api/students/{id}</h3>
                        <p>Retrieves a specific student's data by ID.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "student": {
        "id": 1,
        "name": "Student Name",
        "email": "student@example.com",
        "role": "student",
        "nis": "123456",
        "nisn": "123456789",
        "class": "XI-A",
        "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
        // other student attributes
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-warning text-dark me-2">PUT</span> /api/students/{id}</h3>
                        <p>Updates a student's information.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Request Body
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "name": "Updated Name",
    "phone_number": "08123456789",
    "address": "Updated Address"
}</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "message": "Data siswa berhasil diperbarui",
    "student": {
        // updated student data
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-primary me-2">POST</span> /api/students/profile-photo</h3>
                        <p>Updates a student's profile photo.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Form Data
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>profile_photo: [File]
id: 1</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "message": "Foto profil siswa berhasil diperbarui",
    "profile_photo": "profile-photos/image.jpg",
    "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Teacher Endpoints -->
                    <h2 class="h4 mt-5 mb-3 border-bottom pb-2">Teacher Endpoints</h2>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-success me-2">GET</span> /api/teachers</h3>
                        <p>Retrieves teacher data. For teacher users, returns only their own data.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "teachers": [
        {
            "id": 2,
            "name": "Teacher Name",
            "email": "teacher@example.com",
            "role": "teacher",
            "nip": "987654",
            "nuptk": "987654321",
            "subject": "Mathematics",
            "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
            // other teacher attributes
        }
    ],
    "count": 1
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-success me-2">GET</span> /api/teachers/{id}</h3>
                        <p>Retrieves a specific teacher's data by ID.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "teacher": {
        "id": 2,
        "name": "Teacher Name",
        "email": "teacher@example.com",
        "role": "teacher",
        "nip": "987654",
        "nuptk": "987654321",
        "subject": "Mathematics",
        "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
        // other teacher attributes
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-warning text-dark me-2">PUT</span> /api/teachers/{id}</h3>
                        <p>Updates a teacher's information.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Request Body
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "name": "Updated Name",
    "phone_number": "08123456789",
    "address": "Updated Address"
}</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "message": "Data guru berhasil diperbarui",
    "teacher": {
        // updated teacher data
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="endpoint mb-4">
                        <h3 class="h5 mb-3"><span class="badge bg-primary me-2">POST</span> /api/teachers/profile-photo</h3>
                        <p>Updates a teacher's profile photo.</p>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Headers
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>Authorization: Bearer YOUR_TOKEN</code></pre>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                Form Data
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>profile_photo: [File]
id: 2</code></pre>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-light">
                                Response (200 OK)
                            </div>
                            <div class="card-body">
<pre class="mb-0"><code>{
    "message": "Foto profil guru berhasil diperbarui",
    "profile_photo": "profile-photos/image.jpg",
    "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
}</code></pre>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flutter Integration -->
                    <h2 class="h4 mt-5 mb-3 border-bottom pb-2">Flutter Integration</h2>
                    
                    <p>
                        For detailed Flutter integration guide and code examples, please refer to our 
                        <a href="{{ route('documentation.download') }}" class="text-decoration-none">
                            API Integration Guide <i class="fas fa-download ms-1"></i>
                        </a>
                    </p>
                    
                    <!-- Security Considerations -->
                    <h2 class="h4 mt-5 mb-3 border-bottom pb-2">Security Considerations</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-shield-alt me-2 text-primary"></i>Authentication</h5>
                                    <p class="card-text">All API endpoints (except login) require authentication via Laravel Sanctum tokens.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-user-lock me-2 text-primary"></i>Authorization</h5>
                                    <p class="card-text">Students and teachers can only access their own data, not other users' data.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-user-plus me-2 text-primary"></i>Registration</h5>
                                    <p class="card-text">Registration is disabled in the API to ensure accounts can only be created through the admin dashboard.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-check-circle me-2 text-primary"></i>Validation</h5>
                                    <p class="card-text">All data updates are validated on the server side to prevent invalid inputs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Error Handling -->
                    <h2 class="h4 mt-5 mb-3 border-bottom pb-2">Error Handling</h2>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Status Code</th>
                                    <th>Description</th>
                                    <th>Example Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><span class="badge bg-danger">401</span></td>
                                    <td>Unauthorized (invalid or expired token)</td>
                                    <td><code>{"message": "Unauthenticated."}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><span class="badge bg-danger">403</span></td>
                                    <td>Forbidden (insufficient permissions)</td>
                                    <td><code>{"message": "Unauthorized"}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><span class="badge bg-danger">404</span></td>
                                    <td>Not Found (resource doesn't exist)</td>
                                    <td><code>{"message": "Siswa tidak ditemukan"}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><span class="badge bg-danger">422</span></td>
                                    <td>Validation Error (invalid input)</td>
                                    <td><code>{"message": "Validasi gagal", "errors": {...}}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><span class="badge bg-danger">500</span></td>
                                    <td>Server Error</td>
                                    <td><code>{"message": "Server Error"}</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    pre {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 1rem;
    }
    
    .endpoint {
        border-left: 3px solid #dee2e6;
        padding-left: 1.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
    }
</style>
@endsection 