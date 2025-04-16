<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'student')
            ->select('nis', 'nisn', 'name', 'gender', 'birth_date', 'address', 'phone_number', 'email', 'academic_year', 'parent_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Alamat',
            'No. Telepon',
            'Email',
            'Tahun Akademik',
            'Orang Tua/Wali',
        ];
    }
}
