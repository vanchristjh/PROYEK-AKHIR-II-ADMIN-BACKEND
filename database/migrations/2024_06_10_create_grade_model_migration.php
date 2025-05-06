<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is now handled by 2024_06_10_000001_create_grades_table.php
        // We'll only create the Grade model file if it doesn't exist
        $this->createGradeModel();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No table operations needed
    }

    /**
     * Create the Grade model file
     */
    private function createGradeModel()
    {
        $modelPath = app_path('Models/Grade.php');
        
        if (!file_exists($modelPath)) {
            $modelContent = <<<'MODEL'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'classroom_id',
        'assignment_id',
        'score',
        'max_score',
        'type',
        'feedback',
        'semester',
        'academic_year',
        'modified_by_admin',
        'admin_id',
    ];

    /**
     * Student who received the grade
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Teacher who gave the grade
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Subject for which the grade was given
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Classroom the student belongs to
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Assignment related to this grade (if applicable)
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Calculate percentage score
     */
    public function getPercentageAttribute()
    {
        if (!$this->max_score) return 0;
        return round(($this->score / $this->max_score) * 100);
    }

    /**
     * Letter grade based on percentage
     */
    public function getLetterGradeAttribute()
    {
        $percentage = $this->percentage;
        
        return match(true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'E',
        };
    }

    /**
     * Admin who modified this grade (if applicable)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
MODEL;

            file_put_contents($modelPath, $modelContent);
        }
    }
};
