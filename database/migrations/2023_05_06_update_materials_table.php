<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration is no longer needed as the table is created with nullable file_path in the create_materials_table migration
        // We'll keep this file to avoid confusion but make it a no-op migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No operation needed
    }
};
