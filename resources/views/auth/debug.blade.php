@php
// Temporary debugging file to track auth status for admin login
$user = auth()->user();
@endphp

<h1>Admin Authentication Test</h1>

<h2>Authentication Status</h2>
<p>Is user authenticated: {{ auth()->check() ? 'Yes' : 'No' }}</p>

@if(auth()->check())
    <h2>User Details</h2>
    <ul>
        <li>ID: {{ $user->id }}</li>
        <li>Name: {{ $user->name }}</li>
        <li>Username: {{ $user->username }}</li>
        <li>Role ID: {{ $user->role_id }}</li>
        <li>Role Name: {{ $user->role->name ?? 'No role' }}</li>
        <li>Role Slug: {{ $user->role->slug ?? 'No slug' }}</li>
    </ul>
    
    <h2>Role Check Methods</h2>
    <ul>
        <li>isAdmin(): {{ $user->isAdmin() ? 'True' : 'False' }}</li>
        <li>isGuru(): {{ $user->isGuru() ? 'True' : 'False' }}</li>
        <li>isStudent(): {{ $user->isStudent() ? 'True' : 'False' }}</li>
        <li>hasRole('admin'): {{ $user->hasRole('admin') ? 'True' : 'False' }}</li>
    </ul>
    
    <h2>Dashboard Links</h2>
    <ul>
        <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
        @if($user->role->slug == 'admin')
            <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard (Role-specific link)</a></li>
        @endif
    </ul>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@else
    <p>Not logged in.</p>
    <a href="{{ route('login') }}">Login</a>
@endif
