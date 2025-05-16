<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman: {{ $announcement->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px;
            color: #1a202c;
        }
        .important-badge {
            display: inline-block;
            background-color: #f56565;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 15px;
            margin-bottom: 10px;
        }
        .announcement-meta {
            font-size: 14px;
            color: #718096;
            margin-bottom: 20px;
        }
        .meta-item {
            margin-bottom: 5px;
        }
        .announcement-content {
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            font-size: 12px;
            color: #718096;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMAN 1 GIRI BANYUWANGI</h1>
        <p>Jl. Giri, Banyuwangi, East Java</p>
    </div>

    <div>
        <h1>{{ $announcement->title }}</h1>
        
        @if($announcement->is_important)
            <div class="important-badge">PENTING</div>
        @endif
        
        <div class="announcement-meta">
            <div class="meta-item"><strong>Pembuat:</strong> {{ $announcement->author->name }}</div>
            <div class="meta-item"><strong>Tanggal Publikasi:</strong> {{ $announcement->publish_date->format('d M Y, H:i') }}</div>
            @if($announcement->expiry_date)
                <div class="meta-item"><strong>Tanggal Kedaluwarsa:</strong> {{ $announcement->expiry_date->format('d M Y, H:i') }}</div>
            @endif
            <div class="meta-item"><strong>Target Audiens:</strong> 
                {{ $announcement->audience == 'all' ? 'Semua' : ($announcement->audience == 'teachers' ? 'Guru' : 'Siswa') }}
            </div>
        </div>
        
        <div class="announcement-content">
            {!! nl2br(e($announcement->content)) !!}
        </div>
        
        @if($announcement->attachment)
            <div>
                <p><strong>Lampiran:</strong> {{ basename($announcement->attachment) }}</p>
                <p>* Lampiran hanya tersedia di platform digital</p>
            </div>
        @endif
    </div>
    
    <div class="footer">
        <p>Dokumen ini diekspor pada {{ now()->format('d M Y, H:i') }}</p>
        <p>SMAN 1 GIRI - Sistem Informasi Pengumuman</p>
    </div>
</body>
</html>
