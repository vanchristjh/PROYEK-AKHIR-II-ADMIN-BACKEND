<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Only run this migration if the announcements table already exists
        if (Schema::hasTable('announcements')) {
            // First, check the current structure of the table
            $columns = DB::select('SHOW COLUMNS FROM announcements');
            $columnNames = array_column($columns, 'Field');

            Schema::table('announcements', function (Blueprint $table) use ($columnNames) {
                // Check and add columns if they don't exist

                // Check for audience column with correct type
                if (!in_array('audience', $columnNames)) {
                    $table->enum('audience', ['all', 'administrators', 'teachers', 'students'])->default('all')->after('content');
                } else {
                    // If audience column exists but has wrong type, modify it
                    try {
                        DB::statement("ALTER TABLE announcements MODIFY COLUMN audience ENUM('all', 'administrators', 'teachers', 'students') NOT NULL DEFAULT 'all'");
                    } catch (\Exception $e) {
                        // Error handling if the modification fails
                    }
                }

                // Check for is_important column
                if (!in_array('is_important', $columnNames)) {
                    $table->boolean('is_important')->default(false)->after('audience');
                }

                // Check for attachment column
                if (!in_array('attachment', $columnNames) && !in_array('attachment_path', $columnNames)) {
                    $table->string('attachment')->nullable()->after('audience');
                }

                // Add author_id foreign key if needed
                if (in_array('author_id', $columnNames)) {
                    try {
                        if (!$this->hasForeignKey('announcements', 'author_id')) {
                            $table->foreign('author_id')
                                ->references('id')
                                ->on('users')
                                ->onDelete('cascade');
                        }
                    } catch (\Exception $e) {
                        // Error handling if the foreign key already exists
                    }
                }

                // Make sure publish_date exists
                if (!in_array('publish_date', $columnNames)) {
                    $table->timestamp('publish_date')->nullable()->after('is_important');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse these modifications as they are additive
    }

    /**
     * Check if the table has a foreign key constraint
     *
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function hasForeignKey($table, $column)
    {
        $foreignKeys = DB::select(
            "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '$table'
            AND COLUMN_NAME = '$column'
            AND REFERENCED_TABLE_NAME IS NOT NULL"
        );

        return count($foreignKeys) > 0;
    }
};