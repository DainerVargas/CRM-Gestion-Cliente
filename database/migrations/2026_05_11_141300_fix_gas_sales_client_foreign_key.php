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
            // Drop the existing foreign key
            $table->dropForeign(['client_id']);
            
            // Re-create it with cascade on delete
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_sales', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->foreign('client_id')
                ->references('id')
                ->on('clients');
        });
    }
};
