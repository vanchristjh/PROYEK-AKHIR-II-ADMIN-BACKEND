<table>
    <thead>
        <tr>
            <th colspan="5">JADWAL MENGAJAR</th>
        </tr>
        <tr>
            <th colspan="5">Guru: {{ $teacher->name }} - Diekspor pada: {{ now()->format('d/m/Y H:i') }}</th>
        </tr>
    </thead>
</table>

@foreach($schedulesByDay as $day => $daySchedules)
    <table>
        <thead>
            <tr>
                <th colspan="5">{{ $day }}</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Jam</th>
                <th>Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daySchedules as $index => $schedule)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $schedule->subject->name }}</td>
                    <td>{{ $schedule->classroom->name }}</td>
                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                    <td>{{ $schedule->room ?? 'Belum ditentukan' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
@endforeach
