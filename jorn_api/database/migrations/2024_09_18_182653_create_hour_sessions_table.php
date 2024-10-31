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
        Schema::create('hour_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->date('date'); // Fecha de la hora trabajada
            $table->time('start_time'); // Hora de inicio
            $table->time('end_time'); // Hora de fin
            $table->integer('planned_hours'); // Horas planeadas
            $table->boolean('is_holiday')->default(false)->nullable();
            $table->boolean('is_overtime')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**j
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hour_sessions');
    }
};
