<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <style>
        body {
            font-family: dejavu serif, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA SISWA</h1>
        <h2>SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h2>
        <p>Tahun Akademik: {{ date('Y') }}/{{ date('Y') + 1 }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">NIS</th>
                <th width="10%">NISN</th>
                <th width="20%">Nama</th>
                <th width="15%">Kelas</th>
                <th width="15%">Tahun Akademik</th>
                <th width="10%">Jenis Kelamin</th>
                <th width="15%">Orang Tua</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $index => $student)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $student->nis ?? '-' }}</td>
                <td>{{ $student->nisn ?? '-' }}</td>
                <td>{{ $student->name }}</td>
                <td>
                    @php
                        // Handle class information safely
                        $className = '-';
                        if (isset($student->class_id)) {
                            $class = \App\Models\ClassRoom::find($student->class_id);
                            if ($class) {
                                $className = $class->name;
                            }
                        }
                    @endphp
                    {{ $className }}
                </td>
                <td>{{ $student->academic_year ?? '-' }}</td>
                <td>{{ ($student->gender == 'male' || $student->gender == 'L') ? 'Laki-laki' : 'Perempuan' }}</td>
                <td>{{ $student->parent_name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Belum ada data siswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $date }}</p>
    </div>
</body>
</html>
