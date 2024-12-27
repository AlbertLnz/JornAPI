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
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->decimal('normal_hourly_rate', 8, 2);   // Tarifa Horaria Normal
            $table->decimal('overtime_hourly_rate', 8, 2); // Tarifa Horaria Extra
            $table->decimal('holiday_hourly_rate', 8, 2);  // Tarifa Festivo
            $table->decimal('irpf', 5, 2)->nullable();
            $table->foreignUuid('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
