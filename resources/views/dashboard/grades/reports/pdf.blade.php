<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Akademik - {{ $student->name }}</title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .student-info {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .grades-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .subject-header {
            background-color: #eaeaea;
            font-weight: bold;
        }
        .category-header {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .grade-a {
            color: #28a745;
        }
        .grade-b {
            color: #007bff;
        }
        .grade-c {
            color: #17a2b8;
        }
        .grade-d {
            color: #ffc107;
        }
        .grade-e {
            color: #dc3545;
        }
        .signature {
            margin-top: 50px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
        }
        .signature-column {
            width: 30%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAPOR AKADEMIK</h1>
        <h2>SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h2>
        <p>Tahun Akademik {{ $academic_year }} - Semester {{ $semester }}</p>
    </div>
    
    <div class="student-info">
        <table class="info-table">
            <tr>
                <td width="150">Nama Siswa</td>
                <td width="10">:</td>
                <td>{{ $student->name }}</td>
                <td width="150">Tahun Akademik</td>
                <td width="10">:</td>
                <td>{{ $academic_year }}</td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $student->nis ?? 'N/A' }} / {{ $student->nisn ?? 'N/A' }}</td>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $semester }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $student->class->name ?? 'N/A' }}</td>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>
    
    <h3>HASIL PENILAIAN</h3>
    
    @forelse($subjects as $subjectName => $subjectData)
        <table class="grades-table">
            <tr class="subject-header">
                <th colspan="5">{{ $subjectName }}</th>
            </tr>
            <tr>
                <th width="40%">Kategori Penilaian</th>
                <th width="15%">Bobot</th>
                <th width="15%">Nilai</th>
                <th width="15%">Grade</th>
                <th width="15%">Nilai Akhir</th>
            </tr>
            
            @foreach($subjectData['categories'] as $categoryName => $categoryData)
                <tr>
                    <td>{{ $categoryName }}</td>
                    <td>{{ $categoryData['weight'] }}%</td>
                    <td>{{ number_format($categoryData['average'], 2) }}</td>
                    <td>
                        <span class="grade-{{ strtolower($categoryData['letter_grade']) }}">
                            {{ $categoryData['letter_grade'] }}
                        </span>
                    </td>
                    <td>{{ number_format($categoryData['weighted_score'], 2) }}</td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="2">Total Nilai Tertimbang</td>
                <td>{{ number_format($subjectData['weighted_average'], 2) }}</td>
                <td>
                    @php
                        $grade = '';
                        $average = $subjectData['weighted_average'];
                        if ($average >= 90) $grade = 'A';
                        elseif ($average >= 80) $grade = 'B';
                        elseif ($average >= 70) $grade = 'C';
                        elseif ($average >= 60) $grade = 'D';
                        else $grade = 'E';
                    @endphp
                    <span class="grade-{{ strtolower($grade) }}">{{ $grade }}</span>
                </td>
                <td>{{ number_format($subjectData['total_score'], 2) }}</td>
            </tr>
        </table>
        
        <h4>Detail Penilaian: {{ $subjectName }}</h4>
        
        @foreach($subjectData['categories'] as $categoryName => $categoryData)
            <h5>{{ $categoryName }} (Bobot: {{ $categoryData['weight'] }}%)</h5>
            
            <table class="grades-table">
                <tr>
                    <th width="40%">Nama Penilaian</th>
                    <th width="15%">Tanggal</th>
                    <th width="15%">Nilai Max</th>
                    <th width="15%">Nilai</th>
                    <th width="15%">Grade</th>
                </tr>
                
                @foreach($categoryData['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['date'] }}</td>
                        <td>{{ $item['max_score'] }}</td>
                        <td>{{ $item['score'] ?? 'Belum dinilai' }}</td>
                        <td>
                            @if($item['score'])
                                <span class="grade-{{ strtolower($item['letter_grade']) }}">
                                    {{ $item['letter_grade'] }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @endforeach
        
        <div class="page-break"></div>
    @empty
        <p>Tidak ada data nilai yang tersedia.</p>
    @endforelse
    
    <div class="summary">
        <h3>REKAPITULASI NILAI</h3>
        <table class="grades-table">
            <tr>
                <th>No.</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Akhir</th>
                <th>Grade</th>
            </tr>
            
            @php $i = 1; @endphp
            @foreach($subjects as $subjectName => $subjectData)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $subjectName }}</td>
                    <td>{{ number_format($subjectData['weighted_average'], 2) }}</td>
                    <td>
                        @php
                            $grade = '';
                            $average = $subjectData['weighted_average'];
                            if ($average >= 90) $grade = 'A';
                            elseif ($average >= 80) $grade = 'B';
                            elseif ($average >= 70) $grade = 'C';
                            elseif ($average >= 60) $grade = 'D';
                            else $grade = 'E';
                        @endphp
                        <span class="grade-{{ strtolower($grade) }}">{{ $grade }}</span>
                    </td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="2">RATA-RATA</td>
                <td>{{ number_format($overall_average, 2) }}</td>
                <td>
                    <span class="grade-{{ strtolower($overall_letter_grade) }}">
                        {{ $overall_letter_grade }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="signature">
        <table width="100%">
            <tr>
                <td width="33%">
                    <div class="signature-column">
                        <p>Mengetahui,<br>Orang Tua/Wali</p>
                        <div class="signature-line">&nbsp;</div>
                    </div>
                </td>
                <td width="33%">
                    <div class="signature-column">
                        <p>Wali Kelas</p>
                        <div class="signature-line">
                            {{ $student->class->teacher->name ?? '_______________' }}
                        </div>
                    </div>
                </td>
                <td width="33%">
                    <div class="signature-column">
                        <p>Girsang Sipangan Bolon, {{ now()->format('d F Y') }}<br>Kepala Sekolah</p>
                        <div class="signature-line">Drs. Nama Kepala Sekolah</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>SMA Negeri 1 Girsang Sipangan Bolon - Sistem Informasi Akademik</p>
    </div>
</body>
</html>
