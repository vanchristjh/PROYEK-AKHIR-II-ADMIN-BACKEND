@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard Guru</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Pengumpulan</div>
            <div class="stat-value">{{ $totalPengumpulan ?? 0 }}</div>
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
                <span>Total tugas yang dikumpulkan</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Sudah Dinilai</div>
            <div class="stat-value">{{ $sudahDinilai ?? 0 }}</div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
                <span>Tugas yang sudah dinilai</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Belum Dinilai</div>
            <div class="stat-value">{{ $belumDinilai ?? 0 }}</div>
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
                <span>Tugas yang menunggu penilaian</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-title">Nilai Rata-Rata</div>
            <div class="stat-value">{{ $nilaiRataRata ?? '0.0' }}</div>
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
                <span>Rata-rata nilai tugas</span>
            </div>
        </div>
    </div>
    
    <div class="filters-section">
        <h2 class="filter-title">Filter Pengumpulan</h2>
        
        <form action="{{ route('guru.dashboard') }}" method="GET">
            <div class="filter-group">
                <label class="filter-label" for="tugas">Tugas</label>
                <select class="filter-select" name="tugas" id="tugas">
                    <option value="">Semua Tugas</option>
                    @foreach($tugasList ?? [] as $tugas)
                        <option value="{{ $tugas->id }}" {{ request('tugas') == $tugas->id ? 'selected' : '' }}>
                            {{ $tugas->judul }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="kelas">Kelas</label>
                <select class="filter-select" name="kelas" id="kelas">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList ?? [] as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="status">Status</label>
                <select class="filter-select" name="status" id="status">
                    <option value="">Semua Status</option>
                    <option value="dinilai" {{ request('status') == 'dinilai' ? 'selected' : '' }}>Sudah Dinilai</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dinilai</option>
                </select>
            </div>
            
            <div class="search-box">
                <label class="filter-label" for="cari">Pencarian</label>
                <input type="text" id="cari" name="cari" placeholder="Cari siswa..." value="{{ request('cari') }}">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <div class="filter-actions">
                <a href="{{ route('guru.dashboard') }}" class="btn-reset">
                    <i class="fas fa-undo"></i> Reset Filter
                </a>
                <button type="submit" class="btn-apply">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
    
    <div class="data-table">
        <h2 class="table-title">Daftar Pengumpulan</h2>
        
        <a href="{{ route('guru.export-excel') }}" class="export-btn">
            <i class="fas fa-file-excel"></i> Export ke Excel
        </a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tugas</th>
                    <th>Tanggal Pengumpulan</th>
                    <th>Status</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengumpulan ?? [] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->siswa->nama ?? 'Unknown' }}</td>
                    <td>{{ $item->siswa->kelas->nama ?? 'Unknown' }}</td>
                    <td>{{ $item->tugas->judul ?? 'Unknown' }}</td>
                    <td>{{ $item->tanggal_pengumpulan ? date('d/m/Y H:i', strtotime($item->tanggal_pengumpulan)) : '-' }}</td>
                    <td>
                        @if($item->status == 'dinilai')
                            <span class="badge bg-success">Sudah Dinilai</span>
                        @else
                            <span class="badge bg-warning">Belum Dinilai</span>
                        @endif
                    </td>
                    <td>{{ $item->nilai ?? '-' }}</td>
                    <td>
                        <a href="{{ route('guru.pengumpulan.detail', $item->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pengumpulan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if(isset($pengumpulan) && method_exists($pengumpulan, 'links'))
            <div class="pagination-container">
                {{ $pengumpulan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript functionality here if needed
    });
</script>
@endsection