<?php

namespace App\Exports;

use App\Models\Schedule;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        if (!$teacher) {
            return collect([]);
        }
        
        return Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Hari',
            'Mata Pelajaran',
            'Kelas',
            'Waktu Mulai',
            'Waktu Selesai',
            'Ruangan'
        ];
    }
    
    /**
     * @param mixed $schedule
     * @return array
     */
    public function map($schedule): array
    {
        // Convert day number to name if needed
        $day = $schedule->day;
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
            $day = $dayNames[$day] ?? $day;
        }
        
        return [
            $day,
            $schedule->subject->name ?? 'N/A',
            $schedule->classroom->name ?? 'N/A',
            $schedule->start_time,
            $schedule->end_time,
            $schedule->room ?? '-'
        ];
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Jadwal Mengajar';
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'JADWAL MENGAJAR - ' . Auth::user()->name);
        
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true]],
            'A1:F1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ],
            'A2:F2' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
        ];
    }
}
