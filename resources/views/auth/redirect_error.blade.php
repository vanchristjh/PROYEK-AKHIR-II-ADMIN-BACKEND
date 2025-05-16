@extends('layouts.app')

@section('title', 'Redirect Error')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Redirect Loop Detected</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <p><strong>We've detected a redirect loop.</strong></p>
                        <p>This could be caused by authentication or routing issues.</p>
                    </div>

                    <h5>Debugging Information:</h5>
                    <ul>
                        <li>User ID: {{ $user->id }}</li>
                        <li>Username: {{ $user->username }}</li>
                        <li>Role: {{ $role ?? 'Unknown' }}</li>
                    </ul>

                    <h5>Possible Solutions:</h5>
                    <ol>
                        <li>Clear your cookies and try again</li>
                        <li>Log out and log back in</li>
                        <li>Contact the system administrator</li>
                    </ol>

                    <div class="mt-4">
                        <a href="{{ route('logout') }}" class="btn btn-primary" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log Out and Try Again
                        </a>
                        <a href="{{ url('/clear-cache') }}" class="btn btn-secondary ml-2">Clear Cache</a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
