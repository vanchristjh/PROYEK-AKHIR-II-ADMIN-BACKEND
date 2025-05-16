<?php

namespace App\Exports;

use App\Models\Announcement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class AnnouncementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([$this->announcement]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Konten',
            'Penting',
            'Pemilik',
            'Tanggal Publikasi',
            'Tanggal Kedaluwarsa',
            'Target Audiens',
            'Dibuat pada',
            'Diperbarui pada'
        ];
    }

    /**
     * @param mixed $announcement
     * @return array
     */
    public function map($announcement): array
    {
        return [
            $announcement->id,
            $announcement->title,
            strip_tags(nl2br($announcement->content)),
            $announcement->is_important ? 'Ya' : 'Tidak',
            $announcement->author->name,
            $announcement->publish_date->format('d M Y, H:i'),
            $announcement->expiry_date ? $announcement->expiry_date->format('d M Y, H:i') : 'Tidak ada',
            $announcement->audience == 'all' ? 'Semua' : ($announcement->audience == 'teachers' ? 'Guru' : 'Siswa'),
            $announcement->created_at->format('d M Y, H:i'),
            $announcement->updated_at->format('d M Y, H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Pengumuman';
    }
}
