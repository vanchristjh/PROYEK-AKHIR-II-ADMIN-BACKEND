<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Submission;
use App\Models\AssignmentSubmission;

class MigrateSubmissionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:submissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from submissions table to assignment_submissions table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of submissions data...');

        // Check if submissions table exists
        if (!Schema::hasTable('submissions')) {
            $this->error('The submissions table does not exist!');
            return 1;
        }

        // Check if assignment_submissions table exists
        if (!Schema::hasTable('assignment_submissions')) {
            $this->error('The assignment_submissions table does not exist!');
            return 1;
        }

        // Get all submissions
        $submissions = DB::table('submissions')->get();
        $this->info('Found ' . $submissions->count() . ' submissions to migrate.');

        $migratedCount = 0;
        $skippedCount = 0;

        foreach ($submissions as $submission) {
            // Check if a submission with this assignment_id and student_id already exists in assignment_submissions
            $exists = DB::table('assignment_submissions')
                ->where('assignment_id', $submission->assignment_id)
                ->where('student_id', $submission->student_id)
                ->exists();

            if ($exists) {
                $this->warn("Skipping duplicate submission for assignment {$submission->assignment_id} and student {$submission->student_id}");
                $skippedCount++;
                continue;
            }

            // Insert into assignment_submissions table
            try {
                DB::table('assignment_submissions')->insert([
                    'assignment_id' => $submission->assignment_id,
                    'student_id' => $submission->student_id,
                    'file_path' => $submission->file_path ?? null,
                    'notes' => $submission->notes ?? null,
                    'submission_date' => $submission->submitted_at ?? now(),
                    'grade' => $submission->score ?? null,
                    'graded_at' => $submission->graded_at ?? null,
                    'feedback' => $submission->feedback ?? null,
                    'created_at' => $submission->created_at ?? now(),
                    'updated_at' => $submission->updated_at ?? now(),
                ]);

                $migratedCount++;
            } catch (\Exception $e) {
                $this->error("Error migrating submission {$submission->id}: " . $e->getMessage());
            }
        }

        $this->info("Migration completed: $migratedCount submissions migrated, $skippedCount skipped.");
        
        return 0;
    }
}
