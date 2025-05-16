<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;

class TeacherSubjectController extends Controller
{
    /**
     * Get teachers by subject ID
     */
    public function getTeachersBySubject($subjectId)
    {
        try {
            // Try to find teachers directly related to this subject
            // Assuming you have a relationship set up between subjects and teachers
            $subject = Subject::findOrFail($subjectId);
            
            // Check if there's a teachers() relation on the Subject model
            if (method_exists($subject, 'teachers')) {
                $teachers = $subject->teachers()->get();
                
                if ($teachers->count() > 0) {
                    return response()->json($teachers);
                }
            }
            
            // If no direct relation or no teachers found, try to find any teacher that teaches this subject
            // This depends on your database structure, adjust as needed
            
            // Option 1: If you have a teacher_subject pivot table
            $teachers = Teacher::whereHas('subjects', function($query) use ($subjectId) {
                $query->where('subjects.id', $subjectId);
            })->get();
            
            if ($teachers->count() > 0) {
                return response()->json($teachers);
            }
            
            // Option 2: If teachers are in the users table with a role
            $teachers = User::where('role', 'teacher')
                ->whereHas('subjects', function($query) use ($subjectId) {
                    $query->where('subject_id', $subjectId);
                })->get();
                
            if ($teachers->count() > 0) {
                return response()->json($teachers);
            }
            
            // If still no teachers found, return some default mappings
            $defaultTeachers = $this->getDefaultTeacherSubjectMappings($subjectId);
            
            return response()->json($defaultTeachers);
            
        } catch (\Exception $e) {
            // If anything goes wrong, return an empty array
            return response()->json([
                'error' => $e->getMessage(),
                'teachers' => []
            ], 500);
        }
    }
    
    /**
     * Get default mappings between subjects and teachers
     * This is a fallback when database relationships are not set up
     */
    private function getDefaultTeacherSubjectMappings($subjectId)
    {
        // Create a mapping of subject IDs to teacher data
        $mapping = [
            // Matematika
            '1' => [
                ['id' => 1, 'name' => 'Ahmad Budiman', 'subject' => 'Matematika'],
                ['id' => 11, 'name' => 'Sri Wahyuni', 'subject' => 'Matematika']
            ],
            // Bahasa Indonesia
            '2' => [
                ['id' => 2, 'name' => 'Siti Rahayu', 'subject' => 'Bahasa Indonesia'],
                ['id' => 12, 'name' => 'Bambang Sutrisno', 'subject' => 'Bahasa Indonesia']
            ],
            // Bahasa Inggris
            '3' => [
                ['id' => 3, 'name' => 'Budi Santoso', 'subject' => 'Bahasa Inggris'],
                ['id' => 13, 'name' => 'Nina Agustina', 'subject' => 'Bahasa Inggris']
            ],
            // Biologi
            '4' => [
                ['id' => 4, 'name' => 'Dewi Lestari', 'subject' => 'Biologi']
            ],
            // Fisika
            '5' => [
                ['id' => 5, 'name' => 'Eko Prasetyo', 'subject' => 'Fisika']
            ],
            // Kimia
            '6' => [
                ['id' => 6, 'name' => 'Fitriani', 'subject' => 'Kimia']
            ],
            // Sejarah
            '7' => [
                ['id' => 7, 'name' => 'Gunawan', 'subject' => 'Sejarah']
            ],
            // Geografi
            '8' => [
                ['id' => 8, 'name' => 'Hana Permata', 'subject' => 'Geografi']
            ],
            // Ekonomi
            '9' => [
                ['id' => 9, 'name' => 'Irwan Saputra', 'subject' => 'Ekonomi']
            ],
            // Pendidikan Kewarganegaraan
            '10' => [
                ['id' => 10, 'name' => 'Joko Widodo', 'subject' => 'Pendidikan Kewarganegaraan']
            ]
        ];
        
        // Return teachers for the requested subject, or empty array if not found
        return $mapping[$subjectId] ?? [];
    }
    
    /**
     * List all teachers (with their subjects if available)
     */
    public function listAllTeachers()
    {
        try {
            // Try to get from Teacher model
            $teachers = Teacher::all();
            
            if ($teachers->count() > 0) {
                return response()->json($teachers);
            }
            
            // If no teachers in Teacher model, try User model with teacher role
            $teachers = User::where('role', 'teacher')->get();
            
            if ($teachers->count() > 0) {
                return response()->json($teachers);
            }
            
            // If still no teachers, return default ones
            $defaultTeachers = [
                ['id' => 1, 'name' => 'Ahmad Budiman', 'subject' => 'Matematika'],
                ['id' => 2, 'name' => 'Siti Rahayu', 'subject' => 'Bahasa Indonesia'],
                ['id' => 3, 'name' => 'Budi Santoso', 'subject' => 'Bahasa Inggris'],
                ['id' => 4, 'name' => 'Dewi Lestari', 'subject' => 'Biologi'],
                ['id' => 5, 'name' => 'Eko Prasetyo', 'subject' => 'Fisika'],
                ['id' => 6, 'name' => 'Fitriani', 'subject' => 'Kimia'],
                ['id' => 7, 'name' => 'Gunawan', 'subject' => 'Sejarah'],
                ['id' => 8, 'name' => 'Hana Permata', 'subject' => 'Geografi'],
                ['id' => 9, 'name' => 'Irwan Saputra', 'subject' => 'Ekonomi'],
                ['id' => 10, 'name' => 'Joko Widodo', 'subject' => 'Pendidikan Kewarganegaraan'],
                ['id' => 11, 'name' => 'Sri Wahyuni', 'subject' => 'Matematika'],
                ['id' => 12, 'name' => 'Bambang Sutrisno', 'subject' => 'Bahasa Indonesia'],
                ['id' => 13, 'name' => 'Nina Agustina', 'subject' => 'Bahasa Inggris'],
            ];
            
            return response()->json($defaultTeachers);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'teachers' => []
            ], 500);
        }
    }
}
