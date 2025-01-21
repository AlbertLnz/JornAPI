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
        Schema::rename('hour_workeds', 'hours_worked');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('hours_worked', 'hour_workeds');
    }
};
