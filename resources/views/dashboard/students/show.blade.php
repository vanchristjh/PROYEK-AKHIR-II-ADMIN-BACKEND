@extends('layouts.dashboard')

@section('page-title', 'Detail Siswa')

@section('page-actions')
<div class="d-flex">
    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary btn-sm me-2">
        <i class="bx bx-edit me-1"></i> Edit
    </a>
    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('dashboard-content')
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center pt-4">
                <div class="mb-3">
                    @if($student->profile_photo)
                        <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="rounded-circle img-thumbnail" width="120" height="120">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2d4059&color=fff&size=120" alt="{{ $student->name }}" class="rounded-circle img-thumbnail" width="120" height="120">
                    @endif
                </div>
                <h5 class="mb-1">{{ $student->name }}</h5>
                <p class="text-muted mb-3">{{ $student->nis ?? 'NIS tidak tersedia' }}</p>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">
                        <i class="bx bx-edit me-1"></i> Edit Data
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Akademik</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Kelas</span>
                        <span class="fw-medium">{{ $student->class->name ?? 'Belum ditentukan' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Tahun Akademik</span>
                        <span class="fw-medium">{{ $student->academic_year ?? 'Belum ditentukan' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">NIS</span>
                        <span class="fw-medium">{{ $student->nis ?? 'Tidak tersedia' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">NISN</span>
                        <span class="fw-medium">{{ $student->nisn ?? 'Tidak tersedia' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('attendance.create', ['student_id' => $student->id]) }}" class="btn btn-outline-primary">
                        <i class="bx bx-calendar-check me-1"></i> Input Kehadiran
                    </a>
                    <a href="{{ route('attendance.report', ['student_id' => $student->id]) }}" class="btn btn-outline-info">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Lihat Laporan Kehadiran
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="printStudentDetails()">
                        <i class="bx bx-printer me-1"></i> Cetak Data Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Data Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <p class="mb-0">{{ $student->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <p class="mb-0">{{ $student->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Tanggal Lahir</label>
                        <p class="mb-0">{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d F Y') : 'Tidak tersedia' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Jenis Kelamin</label>
                        <p class="mb-0">{{ ($student->gender == 'male' || $student->gender == 'L') ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nomor Telepon</label>
                        <p class="mb-0">{{ $student->phone_number ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Alamat</label>
                        <p class="mb-0">{{ $student->address ?? 'Tidak tersedia' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Data Orang Tua/Wali</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nama Orang Tua/Wali</label>
                        <p class="mb-0">{{ $student->parent_name ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Nomor Telepon Orang Tua/Wali</label>
                        <p class="mb-0">{{ $student->parent_phone ?? 'Tidak tersedia' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Statistics Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Statistik Kehadiran</h5>
                <span class="badge bg-primary">{{ now()->format('F Y') }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Hadir</span>
                                    <span class="badge bg-success">{{ $attendanceStats['hadir'] ?? 0 }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceStats['hadir_percent'] ?? 0 }}%" aria-valuenow="{{ $attendanceStats['hadir'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Izin</span>
                                    <span class="badge bg-info">{{ $attendanceStats['izin'] ?? 0 }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $attendanceStats['izin_percent'] ?? 0 }}%" aria-valuenow="{{ $attendanceStats['izin'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Sakit</span>
                                    <span class="badge bg-warning">{{ $attendanceStats['sakit'] ?? 0 }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $attendanceStats['sakit_percent'] ?? 0 }}%" aria-valuenow="{{ $attendanceStats['sakit'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Alpa</span>
                                    <span class="badge bg-danger">{{ $attendanceStats['alpa'] ?? 0 }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $attendanceStats['alpa_percent'] ?? 0 }}%" aria-valuenow="{{ $attendanceStats['alpa'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('attendance.report', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-show me-1"></i> Lihat Detail Kehadiran
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Username/Email</label>
                        <p class="mb-0">{{ $student->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small">Status Akun</label>
                        <p class="mb-0">
                            @if(isset($student->email_verified_at))
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning">Belum Terverifikasi</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Tanggal Daftar</label>
                        <p class="mb-0">{{ $student->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Terakhir Diupdate</label>
                        <p class="mb-0">{{ $student->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data siswa <strong>{{ $student->name }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data yang terkait dengan siswa ini.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('students.destroy', $student) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Section -->
<div id="printSection" style="display: none;">
    <div class="row mb-4">
        <div class="col-3 text-center">
            @if($student->profile_photo)
                <img src="{{ asset('storage/'.$student->profile_photo) }}" alt="{{ $student->name }}" class="img-thumbnail" width="150">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=2d4059&color=fff&size=150" alt="{{ $student->name }}" class="img-thumbnail" width="150">
            @endif
        </div>
        <div class="col-9">
            <table class="table table-borderless">
                <tr>
                    <td width="150">Nama</td>
                    <td width="20">:</td>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td>{{ $student->nis ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ $student->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>{{ $student->class->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tahun Akademik</td>
                    <td>:</td>
                    <td>{{ $student->academic_year ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <h5>Data Pribadi</h5>
    <table class="table table-bordered">
        <tr>
            <td width="30%">Tanggal Lahir</td>
            <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>{{ ($student->gender == 'male' || $student->gender == 'L') ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>{{ $student->address ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Telepon</td>
            <td>{{ $student->phone_number ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $student->email }}</td>
        </tr>
        <tr>
            <td>Nama Orang Tua/Wali</td>
            <td>{{ $student->parent_name ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Telepon Orang Tua/Wali</td>
            <td>{{ $student->parent_phone ?? '-' }}</td>
        </tr>
    </table>
    <div class="mt-5 text-end">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function printStudentDetails() {
        const printContents = document.getElementById('printSection').innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div class="container py-4">
                <div class="text-center mb-4">
                    <h4>DATA SISWA</h4>
                    <h5>SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h5>
                </div>
                ${printContents}
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
@endsection
