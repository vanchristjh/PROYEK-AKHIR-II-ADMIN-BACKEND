<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Kelas {{ $classroom->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .header p {
            font-size: 14px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .day-heading {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            background-color: #f2f2f2;
            padding: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>JADWAL PELAJARAN</h1>
        <p>Kelas {{ $classroom->name }}</p>
        <p>SMAN 1 Girsip</p>
        <p>Tahun Ajaran {{ date('Y') }} - {{ date('Y') + 1 }}</p>
    </div>
    
    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
        @if(isset($schedulesByDay[$day]) && count($schedulesByDay[$day]) > 0)
            <div class="day-heading">{{ $day }}</div>
            <table>
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedulesByDay[$day] as $schedule)
                        <tr>
                            <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                            <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->teacher->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
    
    <div class="footer">
        <p>Dicetak pada tanggal: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
