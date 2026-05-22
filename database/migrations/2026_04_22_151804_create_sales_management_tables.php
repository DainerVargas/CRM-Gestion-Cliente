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
        Schema::create('sales_sessions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->decimal('starting_cash', 10, 2)->default(0);
            $table->decimal('closing_cash', 10, 2)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('gas_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained();
            $table->string('client_name_manual')->nullable();
            $table->string('cylinder_type'); // k10, s45, s10, etc.
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'yape', 'card', 'credit'])->default('cash');
            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_session_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('category'); // food, fuel, advance, etc.
            $table->timestamps();
        });

        Schema::create('cylinder_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_session_id')->constrained()->onDelete('cascade');
            $table->string('cylinder_type');
            $table->integer('initial_full')->default(0);
            $table->integer('initial_empty')->default(0);
            $table->integer('final_full')->nullable();
            $table->integer('final_empty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cylinder_inventory');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('gas_sales');
        Schema::dropIfExists('sales_sessions');
    }
};
