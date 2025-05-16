<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jadwal Mengajar - {{ $teacher->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12pt;
            margin-bottom: 20px;
            color: #666;
        }
        .info {
            font-size: 11pt;
            margin-bottom: 15px;
        }
        .day-header {
            background-color: #f3f4f6;
            font-size: 14pt;
            font-weight: bold;
            padding: 8px;
            margin: 15px 0 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #4b72b0;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 10pt;
            border: 1px solid #b6b6b6;
        }
        td {
            padding: 8px;
            border: 1px solid #b6b6b6;
            font-size: 10pt;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">JADWAL MENGAJAR</div>
        <div class="subtitle">SMA Negeri 1</div>
    </div>
    
    <div class="info">
        <table style="width: 70%; border: none;">
            <tr style="border: none;">
                <td style="width: 120px; border: none; padding: 2px;">Nama Guru</td>
                <td style="width: 10px; border: none; padding: 2px;">:</td>
                <td style="border: none; padding: 2px;"><b>{{ $teacher->name }}</b></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; padding: 2px;">Tanggal Cetak</td>
                <td style="border: none; padding: 2px;">:</td>
                <td style="border: none; padding: 2px;">{{ now()->format('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>
    
    @php
        $dayOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
        $schedulesGrouped = $schedulesByDay->sortBy(function($group, $day) use ($dayOrder) {
            if (is_numeric($day)) return $day;
            return $dayOrder[$day] ?? 999;
        });
    @endphp
    
    @foreach($schedulesGrouped as $day => $daySchedules)
        @php
            if (is_numeric($day)) {
                $dayNames = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu'
                ];
                $dayDisplay = $dayNames[$day] ?? $day;
            } else {
                $dayDisplay = $day;
            }
        @endphp
        
        <div class="day-header">{{ $dayDisplay }}</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Mata Pelajaran</th>
                    <th style="width: 20%;">Kelas</th>
                    <th style="width: 20%;">Jam</th>
                    <th style="width: 30%;">Ruangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daySchedules as $index => $schedule)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->classroom->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                        <td>{{ $schedule->room ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    
    <div class="footer">
        Dokumen ini dicetak oleh sistem GIRSIP pada {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
