<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->info('Foreign key constraints disabled before migrations.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Log information to console during migrations.
     */
    private function info($message): void
    {
        if (app()->runningInConsole()) {
            echo "{$message}\n";
        }
    }
};
