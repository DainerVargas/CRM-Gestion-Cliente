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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('status_changed_by')->nullable()->after('status');
        });

        // Update existing 'client' records to 'libre'
        try {
            // For MySQL: modifying enum
            DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect', 'active', 'inactive', 'client', 'libre') DEFAULT 'prospect'");
            DB::table('clients')->where('status', 'client')->update(['status' => 'libre']);
            DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect', 'active', 'inactive', 'libre') DEFAULT 'prospect'");
        } catch (\Exception $e) {
            // SQLite or other: at least they will have the status_changed_by column
            // We can handle labels in the code if enum change fails.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('status_changed_by');
        });
        
        try {
            DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect', 'active', 'inactive', 'client') DEFAULT 'prospect'");
        } catch (\Exception $e) {}
    }
};
