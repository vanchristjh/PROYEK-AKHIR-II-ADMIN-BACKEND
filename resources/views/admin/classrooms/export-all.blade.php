<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Seluruh Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 20px;
            margin: 5px 0;
        }
        h2 {
            font-size: 18px;
            margin: 20px 0 10px;
            color: #4338ca;
            page-break-after: avoid;
        }
        h3 {
            font-size: 16px;
            color: #555;
            margin: 15px 0 10px;
            page-break-after: avoid;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 8px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .classroom-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .page-break {
            page-break-before: always;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA SELURUH KELAS</h1>
        <h1>SMA NEGERI 1 GIRSIP</h1>
        <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
    </div>
    
    <div class="info-section">
        <h2>Ringkasan</h2>
        <table>
            <tr>
                <td width="30%"><strong>Total Kelas</strong></td>
                <td>{{ $classrooms->count() }} kelas</td>
            </tr>
            <tr>
                <td><strong>Kelas 10</strong></td>
                <td>{{ $classrooms->where('grade_level', 10)->count() }} kelas</td>
            </tr>
            <tr>
                <td><strong>Kelas 11</strong></td>
                <td>{{ $classrooms->where('grade_level', 11)->count() }} kelas</td>
            </tr>
            <tr>
                <td><strong>Kelas 12</strong></td>
                <td>{{ $classrooms->where('grade_level', 12)->count() }} kelas</td>
            </tr>
            <tr>
                <td><strong>Total Siswa</strong></td>
                <td>{{ $classrooms->sum(function($classroom) { return $classroom->students->count(); }) }} siswa</td>
            </tr>
        </table>
    </div>
    
    <h2>Daftar Kelas</h2>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Kelas</th>
                <th width="20%">Tingkat</th>
                <th width="20%">Wali Kelas</th>
                <th width="15%">Jumlah Siswa</th>
                <th width="15%">Kapasitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classrooms as $index => $classroom)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $classroom->name }}</td>
                    <td>Kelas {{ $classroom->grade_level }}</td>
                    <td>{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</td>
                    <td>{{ $classroom->students->count() }}</td>
                    <td>{{ $classroom->capacity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach($classrooms as $index => $classroom)
        <div class="{{ $index > 0 ? 'page-break' : '' }}"></div>
        <div class="classroom-section">
            <h2>{{ $classroom->name }}</h2>
            <table>
                <tr>
                    <td width="30%"><strong>Tingkat</strong></td>
                    <td>Kelas {{ $classroom->grade_level }}</td>
                </tr>
                <tr>
                    <td><strong>Tahun Akademik</strong></td>
                    <td>{{ $classroom->academic_year }}</td>
                </tr>
                <tr>
                    <td><strong>Wali Kelas</strong></td>
                    <td>{{ $classroom->homeroomTeacher->name ?? 'Belum ditetapkan' }}</td>
                </tr>
                <tr>
                    <td><strong>Ruangan</strong></td>
                    <td>{{ $classroom->room_number ?? 'Tidak ditentukan' }}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Siswa</strong></td>
                    <td>{{ $classroom->students->count() }} dari {{ $classroom->capacity }}</td>
                </tr>
            </table>
            
            <h3>Daftar Siswa</h3>
            @if($classroom->students->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="30%">NIS</th>
                            <th width="60%">Nama Lengkap</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classroom->students as $studentIndex => $student)
                            <tr>
                                <td>{{ $studentIndex + 1 }}</td>
                                <td>{{ $student->id_number }}</td>
                                <td>{{ $student->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada siswa yang terdaftar di kelas ini.</p>
            @endif
            
            <h3>Mata Pelajaran</h3>
            @if($classroom->subjects->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th width="30%">Kode</th>
                            <th width="60%">Nama Mata Pelajaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classroom->subjects as $subjectIndex => $subject)
                            <tr>
                                <td>{{ $subjectIndex + 1 }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada mata pelajaran yang ditambahkan ke kelas ini.</p>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        <p>SMA Negeri 1 Girsip</p>
    </div>
</body>
</html>
