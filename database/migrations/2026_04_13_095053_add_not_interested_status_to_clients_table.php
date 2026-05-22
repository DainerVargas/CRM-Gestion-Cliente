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
        try {
            DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect', 'active', 'inactive', 'libre', 'not_interested') DEFAULT 'prospect'");
        } catch (\Exception $e) {
            // Log or handle if not MySQL
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect', 'active', 'inactive', 'libre') DEFAULT 'prospect'");
        } catch (\Exception $e) {
            // Log or handle if not MySQL
        }
    }
};
