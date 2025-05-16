<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Kelas {{ $classroom->name }}</title>
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
        .section {
            margin-bottom: 20px;
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
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 200px;
            display: inline-block;
        }
        .content {
            display: inline-block;
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
    @php
    use Illuminate\Support\Str;
    @endphp
    
    <div class="header">
        <div class="school-name">SMA NEGERI 1 GIRSIP</div>
        <div class="document-title">DETAIL INFORMASI KELAS</div>
    </div>
    
    <div class="section">
        <div class="info-row">
            <span class="label">Nama Kelas:</span>
            <span class="content">{{ $classroom->name }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Tingkat:</span>
            <span class="content">Kelas {{ $classroom->grade_level }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Tahun Akademik:</span>
            <span class="content">{{ $classroom->academic_year }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Ruangan:</span>
            <span class="content">{{ $classroom->room_number ?? 'Belum ditetapkan' }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Kapasitas:</span>
            <span class="content">{{ $classroom->capacity }} siswa</span>
        </div>
        
        <div class="info-row">
            <span class="label">Wali Kelas:</span>
            <span class="content">{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</span>
        </div>
        
        @if($classroom->homeroomTeacher)
        <div class="info-row">
            <span class="label">NIP Wali Kelas:</span>
            <span class="content">{{ $classroom->homeroomTeacher->id_number ?? '-' }}</span>
        </div>
        @endif
    </div>
    
    <div class="section">
        <h2>Mata Pelajaran ({{ $classroom->subjects->count() }})</h2>
        @if($classroom->subjects->isEmpty())
            <p>Tidak ada mata pelajaran yang terdaftar untuk kelas ini.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classroom->subjects as $index => $subject)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $subject->code }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ Str::limit($subject->description, 100) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <div class="section">
        <h2>Daftar Siswa ({{ $classroom->students->count() }}/{{ $classroom->capacity }})</h2>
        @if($classroom->students->isEmpty())
            <p>Tidak ada siswa yang terdaftar di kelas ini.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classroom->students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->id_number ?? '-' }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <div class="footer">
        Dokumen ini dicetak pada: {{ now()->format('d F Y - H:i') }}
    </div>
</body>
</html>
