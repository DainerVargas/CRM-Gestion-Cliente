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
        Schema::table('gas_sales', function (Blueprint $table) {
            $table->foreignId('tv_account_id')->nullable()->after('cylinder_type')->constrained('tv_accounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_sales', function (Blueprint $table) {
            $table->dropForeign(['tv_account_id']);
            $table->dropColumn('tv_account_id');
        });
    }
};
