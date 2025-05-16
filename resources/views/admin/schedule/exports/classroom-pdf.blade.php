<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .school-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .school-header h2 {
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        
        .school-header p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .day-header {
            background-color: #e6e6e6;
            font-weight: bold;
        }
        
        .footer {
            text-align: right;
            margin-top: 50px;
        }
        
        .signature {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <div class="school-header">
        <h2>SMA NEGERI 1 CONTOH</h2>
        <p>Jl. Pendidikan No. 1, Kota Contoh</p>
        <p>Email: info@sman1contoh.sch.id | Telp: (021) 1234567</p>
    </div>
    
    <h1>{{ $title }}</h1>
    
    <div class="date">
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>
    
    @foreach($schedules as $day => $daySchedules)
        <h3 class="day-header">{{ $day }}</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daySchedules as $index => $schedule)
                    <tr>
                        <td style="width: 5%;">{{ $index + 1 }}</td>
                        <td style="width: 20%;">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                        <td style="width: 40%;">{{ $schedule->subject ? $schedule->subject->name : 'N/A' }}</td>
                        <td style="width: 35%;">{{ $schedule->teacher ? $schedule->teacher->name : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
    
    <div class="footer">
        <p>Kota Contoh, {{ $date }}</p>
        <p>Kepala Sekolah,</p>
        <div class="signature"></div>
        <p><strong>Nama Kepala Sekolah</strong></p>
        <p>NIP: 123456789</p>
    </div>
</body>
</html>
