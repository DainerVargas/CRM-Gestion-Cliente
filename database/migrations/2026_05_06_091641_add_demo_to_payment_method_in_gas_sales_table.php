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
        // Using raw SQL because altering ENUMs with Schema builder can be problematic in some MySQL versions
        DB::statement("ALTER TABLE gas_sales MODIFY COLUMN payment_method ENUM('cash', 'yape', 'card', 'credit', 'demo') DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE gas_sales MODIFY COLUMN payment_method ENUM('cash', 'yape', 'card', 'credit') DEFAULT 'cash'");
    }
};
