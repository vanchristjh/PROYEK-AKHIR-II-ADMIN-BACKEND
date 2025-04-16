<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->paginate(10);
        return view('dashboard.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('dashboard.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|unique:users',
            'nuptk' => 'nullable|string|unique:users',
            'subject' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'position' => 'nullable|string',
            'join_date' => 'nullable|date',
            'education_level' => 'nullable|string',
            'education_institution' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'subject' => $request->subject,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'education_level' => $request->education_level,
            'education_institution' => $request->education_institution,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        User::create($userData);

        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil dibuat.');
    }

    public function edit(User $teacher)
    {
        return view('dashboard.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$teacher->id,
            'nip' => 'required|string|unique:users,nip,'.$teacher->id,
            'nuptk' => 'nullable|string|unique:users,nuptk,'.$teacher->id,
            'subject' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'position' => 'nullable|string',
            'join_date' => 'nullable|date',
            'education_level' => 'nullable|string',
            'education_institution' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'subject' => $request->subject,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'education_level' => $request->education_level,
            'education_institution' => $request->education_institution,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($teacher->profile_photo) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        $teacher->update($userData);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $teacher->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        // Delete profile photo if exists
        if ($teacher->profile_photo) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }
        
        $teacher->delete();
        
        return redirect()->route('teachers.index')
            ->with('success', 'Akun guru berhasil dihapus.');
    }

    /**
     * Export teachers data to Excel/CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
        try {
            // Get teacher data
            $teachers = User::where('role', 'teacher')
                ->get();
                
            // Prepare CSV headers
            $filename = 'data_guru_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];
            
            $handle = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to fix special characters in Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add header row
            fputcsv($handle, [
                'NIP',
                'NUPTK',
                'Nama',
                'Jenis Kelamin',
                'Email',
                'Mata Pelajaran',
                'Jabatan',
                'Pendidikan Terakhir',
            ]);
            
            // Add data rows
            foreach ($teachers as $teacher) {
                $gender = ($teacher->gender == 'male' || $teacher->gender == 'L') ? 'Laki-laki' : 'Perempuan';
                
                fputcsv($handle, [
                    $teacher->nip ?? '-',
                    $teacher->nuptk ?? '-',
                    $teacher->name,
                    $gender,
                    $teacher->email,
                    $teacher->subject ?? '-',
                    $teacher->position ?? '-',
                    $teacher->education_level ?? '-',
                ]);
            }
            
            fclose($handle);
            
            return response()->make('', 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    /**
     * Export teachers data to PDF.
     * 
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        try {
            // Get teacher data
            $teachers = User::where('role', 'teacher')
                ->get();
                
            // Generate PDF using DomPDF
            $pdf = \PDF::loadView('exports.teachers-pdf', [
                'teachers' => $teachers,
                'date' => now()->format('d F Y')
            ]);
            
            // Set options
            $pdf->setOptions([
                'defaultFont' => 'dejavu serif',
                'isRemoteEnabled' => true
            ]);
            
            // Set paper size to landscape A4
            $pdf->setPaper('a4', 'landscape');
            
            // Download the PDF file
            return $pdf->download('data_guru_'.date('Y-m-d').'.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error exporting PDF: ' . $e->getMessage());
        }
    }
}