<!-- Ensure you're only showing actual teachers in the dropdown -->
<select name="teacher_id" id="teacher_id" class="form-control">
    <option value="">Pilih Guru</option>
    @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
    @endforeach
</select>
