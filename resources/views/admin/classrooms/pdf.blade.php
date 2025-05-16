<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kelas {{ $classroom->name }}</title>
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
        }
        .header h1 {
            margin: 0;
            color: #4338ca;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #4338ca;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-box {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMAN 1 GIRSIP</h1>
        <p>Data Kelas: {{ $classroom->name }}</p>
        <p>Tahun Akademik: {{ $classroom->academic_year }}</p>
    </div>
    
    <div class="section">
        <h2>Informasi Kelas</h2>
        <div class="info-box">
            <p><span class="info-label">Nama Kelas:</span> {{ $classroom->name }}</p>
            <p><span class="info-label">Tingkat:</span> Kelas {{ $classroom->grade_level }}</p>
            <p><span class="info-label">Kapasitas:</span> {{ $classroom->capacity }} siswa</p>
            <p><span class="info-label">Ruangan:</span> {{ $classroom->room_number ?? 'Belum ditetapkan' }}</p>
            <p><span class="info-label">Wali Kelas:</span> {{ $classroom->homeroomTeacher ? $classroom->homeroomTeacher->name : 'Belum ditetapkan' }}</p>
        </div>
    </div>
    
    <div class="section">
        <h2>Daftar Siswa ({{ $classroom->students->count() }} siswa)</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classroom->students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada siswa dalam kelas ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>Mata Pelajaran ({{ $classroom->subjects->count() }} mata pelajaran)</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classroom->subjects as $index => $subject)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subject->code }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->teacher ? $subject->teacher->name : 'Belum ditetapkan' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada mata pelajaran untuk kelas ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>Dokumen ini dicetak pada {{ date('d-m-Y H:i:s') }}</p>
        <p>SMAN 1 GIRSIP - Sistem Informasi Akademik</p>
    </div>
</body>
</html>
