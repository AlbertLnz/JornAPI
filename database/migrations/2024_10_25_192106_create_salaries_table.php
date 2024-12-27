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
        Schema::create('salaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date')->unique();
            $table->date('end_date')->unique();
            $table->decimal('total_normal_hours', 8, 2)->default(0);
            $table->decimal('total_overtime_hours', 8, 2)->default(0);
            $table->decimal('total_holiday_hours', 8, 2)->default(0)->nullable();
            $table->decimal('total_gross_salary', 8, 2)->default(0)->nullable();
            $table->decimal('total_net_salary', 8, 2)->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
