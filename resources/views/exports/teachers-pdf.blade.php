<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1, h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DATA GURU</h2>
        <h3>SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h3>
        <p class="text-center">Dicetak pada: {{ $date }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">NIP</th>
                <th width="20%">Nama</th>
                <th width="10%">Jenis Kelamin</th>
                <th width="18%">Email</th>
                <th width="15%">Mata Pelajaran</th>
                <th width="10%">Jabatan</th>
                <th width="10%">Pend. Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $index => $teacher)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $teacher->nip ?? '-' }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td class="text-center">
                        @if($teacher->gender == 'L' || $teacher->gender == 'male')
                            Laki-laki
                        @elseif($teacher->gender == 'P' || $teacher->gender == 'female')
                            Perempuan
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->subject ?? '-' }}</td>
                    <td>{{ $teacher->position ?? '-' }}</td>
                    <td class="text-center">{{ $teacher->education_level ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data guru</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} SMA Negeri 1 Girsang Sipangan Bolon - Sistem Informasi Manajemen Sekolah</p>
    </div>
</body>
</html>
