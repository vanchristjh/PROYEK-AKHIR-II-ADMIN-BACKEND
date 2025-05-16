<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Semua Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6b46c1;
            padding-bottom: 10px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            color: #6b46c1;
        }
        h2 {
            color: #6b46c1;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">SMA NEGERI 1 GIRSIP</div>
        <div class="document-title">DAFTAR SEMUA KELAS</div>
    </div>
    
    <h2>Data Kelas ({{ $classrooms->count() }} kelas)</h2>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Tahun Akademik</th>
                <th>Ruangan</th>
                <th>Kapasitas</th>
                <th>Wali Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classrooms as $index => $classroom)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $classroom->name }}</td>
                <td>{{ $classroom->grade_level }}</td>
                <td>{{ $classroom->academic_year }}</td>
                <td>{{ $classroom->room_number ?? 'Belum ditetapkan' }}</td>
                <td>{{ $classroom->capacity }}</td>
                <td>{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Dokumen ini dicetak pada: {{ now()->format('d F Y - H:i') }}
    </div>
</body>
</html>
