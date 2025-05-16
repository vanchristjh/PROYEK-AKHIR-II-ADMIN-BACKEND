<!DOCTYPE html>
<html>
<head>
    <title>Navigation Links</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .link-box { margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .link-box a { display: block; padding: 10px; background: #f0f0f0; text-decoration: none; color: #333; }
        .link-box a:hover { background: #e0e0e0; }
    </style>
</head>
<body>
    <h1>Navigation Links for Testing</h1>
    <p>Use these direct links to test navigation:</p>
    
    <div class="link-box">
        <h2>Dashboard</h2>
        <a href="{{ url('/siswa/dashboard') }}">Dashboard</a>
    </div>
    
    <div class="link-box">
        <h2>Materials</h2>
        <a href="{{ url('/siswa/materials') }}">Materials Page</a>
    </div>
    
    <div class="link-box">
        <h2>Assignments</h2>
        <a href="{{ url('/siswa/assignments') }}">Assignments Page</a>
    </div>
    
    <div class="link-box">
        <h2>Schedule</h2>
        <a href="{{ url('/siswa/schedule') }}">Schedule Page</a>
    </div>
</body>
</html>
