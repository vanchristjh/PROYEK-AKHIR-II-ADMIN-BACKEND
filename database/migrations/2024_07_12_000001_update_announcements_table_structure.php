<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateAnnouncementsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the announcements table exists
        if (Schema::hasTable('announcements')) {
            // Get the current columns
            $columns = DB::select('SHOW COLUMNS FROM announcements');
            $columnNames = array_column($columns, 'Field');
            
            Schema::table('announcements', function (Blueprint $table) use ($columnNames) {
                // Check audience column - make sure it includes 'administrators'
                if (in_array('audience', $columnNames)) {
                    // Check if audience is an enum type and if it doesn't include administrators
                    $audienceColumn = collect(DB::select("SHOW FIELDS FROM announcements WHERE Field = 'audience'"))->first();
                    
                    if ($audienceColumn) {
                        $type = $audienceColumn->Type;
                        
                        if (strpos($type, 'enum') !== false && strpos($type, 'administrators') === false) {
                            // Update the enum to include administrators
                            DB::statement("ALTER TABLE announcements MODIFY COLUMN audience ENUM('all', 'administrators', 'teachers', 'students') NOT NULL DEFAULT 'all'");
                        }
                    }
                } else {
                    // Add audience column if it doesn't exist
                    $table->enum('audience', ['all', 'administrators', 'teachers', 'students'])->default('all')->after('content');
                }
                
                // Check for is_important column
                if (!in_array('is_important', $columnNames)) {
                    $table->boolean('is_important')->default(false);
                }
                
                // Check if there's an attachment or attachment_path column
                if (!in_array('attachment', $columnNames) && !in_array('attachment_path', $columnNames)) {
                    $table->string('attachment')->nullable();
                }
                
                // Check if author_id has a foreign key
                if (in_array('author_id', $columnNames)) {
                    try {
                        $foreignKeys = DB::select(
                            "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                            WHERE TABLE_SCHEMA = DATABASE()
                            AND TABLE_NAME = 'announcements'
                            AND COLUMN_NAME = 'author_id'
                            AND REFERENCED_TABLE_NAME IS NOT NULL"
                        );
                        
                        if (count($foreignKeys) === 0) {
                            // Add foreign key if it doesn't exist
                            try {
                                $table->foreign('author_id')
                                    ->references('id')
                                    ->on('users')
                                    ->onDelete('cascade');
                            } catch (\Exception $e) {
                                // Key might already exist but not visible in our query
                            }
                        }
                    } catch (\Exception $e) {
                        // Error handling if the query fails
                    }
                }
                
                // Make sure required columns exist
                if (!in_array('title', $columnNames)) {
                    $table->string('title');
                }
                
                if (!in_array('content', $columnNames)) {
                    $table->text('content');
                }
                
                if (!in_array('publish_date', $columnNames)) {
                    $table->timestamp('publish_date')->nullable();
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
        // This migration makes additive changes, no need to reverse them
    }
}
