@extends('layouts.dashboard')

@section('page-title', 'Laporan Absensi')

@section('page-actions')
<a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Kembali
</a>
@endsection

@section('dashboard-content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter Laporan</h6>
                        <form action="{{ route('attendance.report') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-select" id="class_id" name="class_id">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="student_id" class="form-label">Siswa</label>
                                <select class="form-select" id="student_id" name="student_id">
                                    <option value="">Semua Siswa</option>
                                    @foreach($students ?? [] as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <option value="">Semua Bulan</option>
                                    @foreach(range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year">
                                    @foreach(range(date('Y') - 2, date('Y')) as $year)
                                        <option value="{{ $year }}" {{ (request('year', date('Y')) == $year) ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-filter-alt me-1"></i> Filter
                                </button>
                                <a href="{{ route('attendance.report') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                                <button type="submit" class="btn btn-success" name="export" value="1">
                                    <i class="bx bx-download me-1"></i> Ekspor ke Excel
                                </button>
                            </div>utton type="button" class="btn btn-info" onclick="printReport()">
                        </form>     <i class="bx bx-printer me-1"></i> Cetak Laporan
                    </div>      </button>
                </div>      </div>
            </div>      </form>
        </div>      </div>
                </div>
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Grafik Kehadiran</h5>
                    </div>="card shadow-sm h-100">
                    <div class="card-body"> bg-white d-flex justify-content-between align-items-center">
                        <canvas id="attendanceChart" height="250"></canvas>
                        <div class="btn-group mt-3" role="group">iv class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary active" id="view-line">Line</button>      <button type="button" class="btn btn-outline-secondary active" id="view-line">
                            <button type="button" class="btn btn-outline-primary" id="view-bar">Bar</button>              <i class="bx bx-line-chart"></i>
                            <button type="button" class="btn btn-outline-primary" id="view-pie">Pie</button>on>
                        </div>class="btn btn-outline-secondary" id="view-bar">
                    </div>art-alt-2"></i>
                </div>
            </div>  <button type="button" class="btn btn-outline-secondary" id="view-pie">
            <div class="col-md-4">x bx-pie-chart-alt-2"></i>
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-primary mb-0">{{ $summary['total_sessions'] ?? 0 }}</h3>
                                    <small class="text-muted">Total Sesi</small>
                                </div>
                            </div>d-title mb-0">Ringkasan</h5>
                            <div class="col-6">="row g-3">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-success mb-0">{{ $summary['attendance_percentage'] ?? '0%' }}</h3>
                                    <small class="text-muted">Kehadiran</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">
                                    <h3 class="text-warning mb-0">{{ $summary['total_students'] ?? 0 }}</h3>
                                    <small class="text-muted">Total Siswa</small> '0%' }}</h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light text-center">iv class="col-6">
                                    <h3 class="text-danger mb-0">{{ $summary['absent_count'] ?? 0 }}</h3>                                <div class="p-3 border rounded bg-light text-center">
                                    <small class="text-muted">Tidak Hadir</small>ass="text-warning mb-0">{{ $summary['total_students'] ?? 0 }}</h3>
                                </div>-muted">Total Siswa</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Persentase Status</h6> class="text-danger mb-0">{{ $summary['absent_count'] ?? 0 }}</h3>
                            <div class="progress-wrapper mb-3">>
                                <span class="d-flex justify-content-between">
                                    <span>Hadir</span>
                                    <span class="text-success">{{ $percentages['hadir'] ?? '0%' }}</span>
                                </span>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $percentages['hadir'] ?? '0%' }}"></div>
                                </div>
                            </div>lass="d-flex justify-content-between">
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Sakit</span>>
                                    <span class="text-info">{{ $percentages['sakit'] ?? '0%' }}</span>iv class="progress mt-1" style="height: 8px;">
                                </span>-success" style="width: {{ $percentages['hadir'] ?? '0%' }}"></div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ $percentages['sakit'] ?? '0%' }}"></div>
                                </div>
                            </div>lass="d-flex justify-content-between">
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Izin</span>>
                                    <span class="text-warning">{{ $percentages['izin'] ?? '0%' }}</span>iv class="progress mt-1" style="height: 8px;">
                                </span>-info" style="width: {{ $percentages['sakit'] ?? '0%' }}"></div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $percentages['izin'] ?? '0%' }}"></div>
                                </div>
                            </div>lass="d-flex justify-content-between">
                            <div class="progress-wrapper mb-3">
                                <span class="d-flex justify-content-between">
                                    <span>Alpa</span>>
                                    <span class="text-danger">{{ $percentages['alpa'] ?? '0%' }}</span>iv class="progress mt-1" style="height: 8px;">
                                </span>      <div class="progress-bar bg-warning" style="width: {{ $percentages['izin'] ?? '0%' }}"></div>
                                <div class="progress mt-1" style="height: 8px;">      </div>
                                    <div class="progress-bar bg-danger" style="width: {{ $percentages['alpa'] ?? '0%' }}"></div>      </div>
                                </div>          <div class="progress-wrapper mb-3">
                            </div>                  <span class="d-flex justify-content-between">
                        </div>                                    <span>Alpa</span>
                    </div> class="text-danger">{{ $percentages['alpa'] ?? '0%' }}</span>
                </div>an>
            </div>ogress mt-1" style="height: 8px;">
        </div>bg-danger" style="width: {{ $percentages['alpa'] ?? '0%' }}"></div>
              </div>
        <!-- Attendance Records Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Data Absensi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>a Absensi</h5>
                                <th>Tanggal</th>
                                <th>Siswa</th>">
                                <th>Kelas</th>e-responsive">
                                <th>Mata Pelajaran</th>s="table table-hover">
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances ?? [] as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>Status</th>
                                <td>{{ \Carbon\Carbon::parse($attendance->session->date)->format('d M Y') }}</td>
                                <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->session->class->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->session->subject->name ?? 'N/A' }}</td>
                                <td>[] as $index => $attendance)
                                    @switch($attendance->status)
                                        @case('hadir')}}</td>
                                            <span class="badge bg-success">Hadir</span>:parse($attendance->session->date)->format('d M Y') }}</td>
                                            @break
                                        @case('izin')->session->class->name ?? 'N/A' }}</td>
                                            <span class="badge bg-warning text-dark">Izin</span>ession->subject->name ?? 'N/A' }}</td>
                                            @break
                                        @case('sakit')dance->status)
                                            <span class="badge bg-info">Sakit</span>
                                            @break
                                        @case('alpa')
                                            <span class="badge bg-danger">Alpa</span>zin')
                                            @break
                                        @case('terlambat')reak
                                            <span class="badge bg-secondary">Terlambat</span>   @case('sakit')
                                            @breakSakit</span>
                                        @default           @break
                                            <span class="badge bg-light text-dark">{{ $attendance->status }}</span>      @case('alpa')
                                    @endswitch            <span class="badge bg-danger">Alpa</span>
                                </td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                            </tr>       <span class="badge bg-secondary">Terlambat</span>
                            @empty           @break
                            <tr> @default
                                <td colspan="7" class="text-center py-4 text-muted">            <span class="badge bg-light text-dark">{{ $attendance->status }}</span>
                                    Tidak ada data absensi yang ditemukan        @endswitch
                                </td>          </td>
                            </tr>                                <td>{{ $attendance->notes ?? '-' }}</td>
                            @endforelse
                        </tbody>
                    </table>      <tr>
                </div>              <td colspan="7" class="text-center py-4 text-muted">
                      Tidak ada data absensi yang ditemukan
                <div class="d-flex justify-content-center mt-4">                      </td>
                    {{ $attendances->links() ?? '' }}                      </tr>
                </div>                 @endforelse
            </div>                        </tbody>
        </div> </table>
    </div>
</div>
@endsection">
endances->links() ?? '' }}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        let currentChart = null;
        
        const chartData = {
            labels: {!! json_encode($chartData['labels'] ?? []) !!},
            datasets: [ContentLoaded', function() {
                {nce Chart
                    label: 'Hadir', = document.getElementById('attendanceChart').getContext('2d');
                    data: {!! json_encode($chartData['hadir'] ?? []) !!},
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)', !!},
                    borderWidth: 1
                },
                {  label: 'Hadir',
                    label: 'Sakit',   data: {!! json_encode($chartData['hadir'] ?? []) !!},
                    data: {!! json_encode($chartData['sakit'] ?? []) !!},r: 'rgba(40, 167, 69, 0.2)',
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 1
                },
                {  label: 'Sakit',
                    label: 'Izin',   data: {!! json_encode($chartData['sakit'] ?? []) !!},
                    data: {!! json_encode($chartData['izin'] ?? []) !!},r: 'rgba(23, 162, 184, 0.2)',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {  label: 'Izin',
                    label: 'Alpa',   data: {!! json_encode($chartData['izin'] ?? []) !!},
                    data: {!! json_encode($chartData['alpa'] ?? []) !!},gba(255, 193, 7, 0.2)',
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                },
                {   label: 'Alpa',
                    label: 'Terlambat',       data: {!! json_encode($chartData['alpa'] ?? []) !!},
                    data: {!! json_encode($chartData['terlambat'] ?? []) !!},          backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    backgroundColor: 'rgba(108, 117, 125, 0.2)',            borderColor: 'rgba(220, 53, 69, 1)',
                    borderColor: 'rgba(108, 117, 125, 1)',erWidth: 1
                    borderWidth: 1
                }
            ]bel: 'Terlambat',
        };n_encode($chartData['terlambat'] ?? []) !!},
108, 117, 125, 0.2)',
        // Chart type switcherrColor: 'rgba(108, 117, 125, 1)',
        function createChart(type) {erWidth: 1
            if (currentChart) {
                currentChart.destroy();
            }

            const config = {, {
                type: type,'line',
                data: chartData,a,
                options: {
                    responsive: true,
                    maintainAspectRatio: falseinAspectRatio: false,
                }
            };
,
            // Add specific options based on chart type   ticks: {
            if (type === 'line' || type === 'bar') {           precision: 0
                config.options.scales = {           }
                    y: {         }
                        beginAtZero: true,        },
                        ticks: {
                            precision: 0
                        }
                    }            },
                };
                config.options.plugins = {
                    legend: {
                        position: 'bottom'    }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }ents)
                };d('class_id');
            } else if (type === 'pie' || type === 'doughnut') {
                // Restructure data for pie/doughnut
                const pieData = {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpa', 'Terlambat'], {
                    datasets: [{
                        data: [
                            chartData.datasets[0].data.reduce((a, b) => a + b, 0),
                            chartData.datasets[1].data.reduce((a, b) => a + b, 0),on>';
                            chartData.datasets[2].data.reduce((a, b) => a + b, 0),
                            chartData.datasets[3].data.reduce((a, b) => a + b, 0),
                            chartData.datasets[4].data.reduce((a, b) => a + b, 0)cator
                        ],
                        backgroundColor: [.innerHTML = '<option value="">Memuat data siswa...</option>';
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(23, 162, 184, 0.7)',dents for this class
                            'rgba(255, 193, 7, 0.7)',`/api/classes/${classId}/students`)
                            'rgba(220, 53, 69, 0.7)',=> {
                            'rgba(108, 117, 125, 0.7)' {
                        ],
                        borderColor: [        throw new Error('Kelas tidak ditemukan');
                            'rgba(40, 167, 69, 1)',e.status === 403) {
                            'rgba(23, 162, 184, 1)',ak memiliki akses');
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)',
                            'rgba(108, 117, 125, 1)'
                        ],
                        borderWidth: 1
                    }]
                };
                config.data = pieData;
                config.options.plugins = {">Semua Siswa</option>';
                    legend: {
                        position: 'bottom'andling empty data
                    }
                };wa</option>';
            }a terdaftar di kelas ini', 'warning');

            currentChart = new Chart(ctx, config);
        }
onst option = document.createElement('option');
        // Initial chart (line)   option.value = student.id;
        createChart('line');       option.textContent = student.name;
        studentSelect.appendChild(option);
        // Chart type buttons
        document.getElementById('view-line').addEventListener('click', function() {
            document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));      // If there was a previously selected student for this class, try to reselect it
            this.classList.add('active');viouslySelected = "{{ request('student_id') }}";
            createChart('line');
        });elected}"]`);

        document.getElementById('view-bar').addEventListener('click', function() {            option.selected = true;
            document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            createChart('bar');
        });

        document.getElementById('view-pie').addEventListener('click', function() {
            document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            createChart('pie');ror loading students:', error);
        });ect.innerHTML = '<option value="">Error: Gagal memuat data</option>';
        udentSelect.disabled = false;
        // Dependent dropdowns (class -> students)
        const classSelect = document.getElementById('class_id');// Add a retry button for convenience
        const studentSelect = document.getElementById('student_id');
        ;
        if (classSelect && studentSelect) {
            classSelect.addEventListener('change', function() {y-2">
                const classId = this.value;>
                btn btn-sm btn-outline-primary float-end" id="retry-load-students">
                // Clear student dropdown         <i class="bx bx-refresh me-1"></i> Coba Lagi
                studentSelect.innerHTML = '<option value="">Semua Siswa</option>';        </button>
                
                if (classId) {
                    // Show loading indicator studentSelect.parentElement.appendChild(retryContainer);
                    studentSelect.disabled = true;           
                    studentSelect.innerHTML = '<option value="">Memuat data siswa...</option>';             document.getElementById('retry-load-students').addEventListener('click', function() {
                                           // Remove the retry container
                    // Make AJAX request to get students for this class                        this.closest('.mt-2').remove();
                    fetch(`/api/classes/${classId}/students`)ass change event
                        .then(response => {ent('change');
                            if (!response.ok) {
                                if (response.status === 404) {
                                    throw new Error('Kelas tidak ditemukan');
                                } else if (response.status === 403) {
                                    throw new Error('Anda tidak memiliki akses');ge}`, 'error');
                                } else {
                                    throw new Error('Gagal memuat data siswa');
                                }
                            }
                            return response.json();
                        })ssages
                        .then(data => {e = 'info') {
                            // Reset dropdownf bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {
                            studentSelect.innerHTML = '<option value="">Semua Siswa</option>';onst toastElement = document.getElementById(`${type}Toast`) || document.getElementById('infoToast');
                            toastElement) {
                            // Handling empty data`${type}ToastMessage`) || document.getElementById('infoToastMessage');
                            if (!data || data.length === 0) { messageElement.textContent = message;
                                studentSelect.innerHTML = '<option value="">Tidak ada siswa</option>';ew bootstrap.Toast(toastElement);
                                showToast('Tidak ada siswa terdaftar di kelas ini', 'warning');   toast.show();
                            } else {   } else {
                                // Add options           // Fallback if toast element isn't available
                                data.forEach(student => {            console.log(`[${type.toUpperCase()}]: ${message}`);
                                    const option = document.createElement('option');
                                    option.value = student.id;
                                    option.textContent = student.name;
                                    studentSelect.appendChild(option);
                                });
                                )}]: ${message}`);
                                // If there was a previously selected student for this class, try to reselect it   if (type === 'error') {
                                const previouslySelected = "{{ request('student_id') }}";           alert(message);
                                if (previouslySelected) {         }
                                    const option = studentSelect.querySelector(`option[value="${previouslySelected}"]`);   }
                                    if (option) {
                                        option.selected = true;        

















































































































































































































































@endsection</script>    });        }            }                classSelect.dispatchEvent(event);                const event = new Event('change');            if (!studentSelect.querySelector('option[value="{{ request('student_id') }}"]')) {            // Only trigger if student_id is not already set (to avoid unnecessary API calls)        if (classSelect && classSelect.value) {        // Trigger class change event on page load if class is already selected                };            printWindow.focus();            printWindow.document.close();                        `);                </html>                </body>                    </div>                        <button class="btn btn-secondary" onclick="window.close()">Tutup</button>                        <button class="btn btn-primary" onclick="window.print()">Cetak</button>                    <div class="no-print mt-3 text-center">                                        </div>                        </div>                            <p>Penanggung jawab,<br><br><br>{{ auth()->user()->name }}</p>                        <div>                        <div></div>                    <div class="mt-5 d-flex justify-content-between">                                        </table>                        </tbody>                            @endforelse                            </tr>                                <td colspan="7" class="text-center">Tidak ada data absensi yang ditemukan</td>                            <tr>                            @empty                            </tr>                                <td>{{ $attendance->notes ?? '-' }}</td>                                </td>                                    @endswitch                                            {{ $attendance->status }}                                        @default                                            @break                                            <span class="badge-secondary">Terlambat</span>                                        @case('terlambat')                                            @break                                            <span class="badge-danger">Alpa</span>                                        @case('alpa')                                            @break                                            <span class="badge-info">Sakit</span>                                        @case('sakit')                                            @break                                            <span class="badge-warning">Izin</span>                                        @case('izin')                                            @break                                            <span class="badge-success">Hadir</span>                                        @case('hadir')                                    @switch($attendance->status)                                <td>                                <td>{{ $attendance->session->subject->name ?? 'N/A' }}</td>                                <td>{{ $attendance->session->class->name ?? 'N/A' }}</td>                                <td>{{ $attendance->student->name ?? 'N/A' }}</td>                                <td>{{ \Carbon\Carbon::parse($attendance->session->date)->format('d M Y') }}</td>                                <td>{{ $index + 1 }}</td>                            <tr>                            @forelse($attendances ?? [] as $index => $attendance)                        <tbody>                        </thead>                            </tr>                                <th>Catatan</th>                                <th>Status</th>                                <th>Mata Pelajaran</th>                                <th>Kelas</th>                                <th>Siswa</th>                                <th>Tanggal</th>                                <th>No</th>                            <tr>                        <thead>                    <table class="table table-bordered">                    <h5>Data Absensi</h5>                                        </div>                        </table>                            </tbody>                                </tr>                                    <td>${'{{ $summary['absent_count'] ?? 0 }}'}</td>                                    <th>Total Tidak Hadir</th>                                <tr>                                </tr>                                    <td>${'{{ $summary['total_students'] ?? 0 }}'}</td>                                    <th>Total Siswa</th>                                <tr>                                </tr>                                    <td>${'{{ $summary['attendance_percentage'] ?? '0%' }}'}</td>                                    <th>Persentase Kehadiran</th>                                <tr>                                </tr>                                    <td>${'{{ $summary['total_sessions'] ?? 0 }}'}</td>                                    <th width="200">Total Sesi</th>                                <tr>                            <tbody>                        <table class="table table-bordered">                        <h5>Ringkasan</h5>                    <div class="mb-4">                                        </div>                        </table>                            </tbody>                                </tr>                                    <td>${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</td>                                    <th>Tanggal Cetak</th>                                <tr>                                </tr>                                    <td>${monthName} ${year}</td>                                    <th>Periode</th>                                <tr>                                </tr>                                    <td>${studentName}</td>                                    <th>Siswa</th>                                <tr>                                </tr>                                    <td>${className}</td>                                    <th width="200">Kelas</th>                                <tr>                            <tbody>                        <table class="table table-bordered">                        <h5>Parameter Laporan</h5>                    <div class="mb-4">                                        </div>                        <hr>                        <h4>SMA NEGERI 1 GIRSANG SIPANGAN BOLON</h4>                        <h3>LAPORAN REKAPITULASI KEHADIRAN SISWA</h3>                    <div class="header">                <body>                </head>                    </style>                        }                            button { display: none; }                            .no-print { display: none; }                        @media print {                        .badge-secondary { background-color: #6c757d; color: white; padding: 2px 6px; border-radius: 4px; }                        .badge-danger { background-color: #dc3545; color: white; padding: 2px 6px; border-radius: 4px; }                        .badge-warning { background-color: #ffc107; color: black; padding: 2px 6px; border-radius: 4px; }                        .badge-info { background-color: #17a2b8; color: white; padding: 2px 6px; border-radius: 4px; }                        .badge-success { background-color: #28a745; color: white; padding: 2px 6px; border-radius: 4px; }                        .header { text-align: center; margin-bottom: 30px; }                        body { font-family: Arial, sans-serif; padding: 20px; }                    <style>                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">                    <title>Laporan Absensi</title>                <head>                <html>            printWindow.document.write(`                        const studentName = studentId !== 'Semua' ? document.querySelector(`#student_id option[value="${studentId}"]`)?.textContent || 'Tidak ditemukan' : 'Semua Siswa';            const className = classId !== 'Semua' ? document.querySelector(`#class_id option[value="${classId}"]`)?.textContent || 'Tidak ditemukan' : 'Semua Kelas';            // Get class and student names                        }                monthName = months[parseInt(month) - 1];                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];            if (month) {            let monthName = 'Semua Bulan';            // Get month name if month is specified                        const year = params.get('year') || new Date().getFullYear();            const month = params.get('month') || 'Semua';            const studentId = params.get('student_id') || 'Semua';            const classId = params.get('class_id') || 'Semua';            const params = new URLSearchParams(window.location.search);            const printWindow = window.open('', '_blank');        window.printReport = function() {        // Print function                }            }                }                    alert(message);                if (type === 'error') {                console.log(`[${type.toUpperCase()}]: ${message}`);            } else {                }                    }                        alert(message);                    if (type === 'error') {                    console.log(`[${type.toUpperCase()}]: ${message}`);                    // Fallback if toast element isn't available                } else {                    toast.show();                    const toast = new bootstrap.Toast(toastElement);                    if (messageElement) messageElement.textContent = message;                    const messageElement = document.getElementById(`${type}ToastMessage`) || document.getElementById('infoToastMessage');                if (toastElement) {                const toastElement = document.getElementById(`${type}Toast`) || document.getElementById('infoToast');            if (typeof bootstrap !== 'undefined' && typeof bootstrap.Toast !== 'undefined') {        function showToast(message, type = 'info') {        // Helper function to show toast messages                }            });                }                        });                            showToast(`Gagal memuat data siswa: ${error.message}`, 'error');                            // Show error message                                                        });                                classSelect.dispatchEvent(event);                                const event = new Event('change');                                // Trigger the class change event                                this.closest('.mt-2').remove();                                // Remove the retry container                            document.getElementById('retry-load-students').addEventListener('click', function() {                                                        studentSelect.parentElement.appendChild(retryContainer);                            `;                                </div>                                    </button>                                        <i class="bx bx-refresh me-1"></i> Coba Lagi                                    <button type="button" class="btn btn-sm btn-outline-primary float-end" id="retry-load-students">                                    <small>${error.message}</small>                                <div class="alert alert-warning py-2">                            retryContainer.innerHTML = `                            retryContainer.className = 'mt-2';                            const retryContainer = document.createElement('div');                            // Add a retry button for convenience                                                        studentSelect.disabled = false;                            studentSelect.innerHTML = '<option value="">Error: Gagal memuat data</option>';                            console.error('Error loading students:', error);                        .catch(error => {                        })                            studentSelect.disabled = false;                            // Re-enable select                                                        }                                }                                    }        // Trigger class change event on page load if class is already selected
        if (classSelect && classSelect.value) {
            // Only trigger if student_id is not already set (to avoid unnecessary API calls)
            if (!studentSelect.querySelector('option[value="{{ request('student_id') }}"]')) {
                const event = new Event('change');
                classSelect.dispatchEvent(event);
            }
        }

        // Print Report Functionality
        function printReport() {
            const printContents = document.querySelector('.card').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    });
</script>
@endsection
