<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Akses Ditolak - {{ config('app.name', 'SMAN 1 Girsip') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .header {
            background: linear-gradient(135deg, #ff6b6b, #f94d4d);
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .header i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            color: #555;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .actions a.logout {
            background: #ff6b6b;
            color: #fff;
        }

        .actions a.logout:hover {
            background: #f94d4d;
        }

        .actions a.back {
            background: #f1f1f1;
            color: #555;
        }

        .actions a.back:hover {
            background: #e2e2e2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-exclamation-circle"></i>
            <h1>Akses Ditolak</h1>
        </div>
        <div class="content">
            <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <div class="actions">
                <form method="POST" action="{{ route('logout') }}" style="flex: 1;">
                    @csrf
                    <button type="submit" class="logout w-full">Logout</button>
                </form>
                <a href="javascript:history.back()" class="back">Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>
