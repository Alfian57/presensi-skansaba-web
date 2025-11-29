<?php

use App\Enums\Day;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('restrict');
            $table->foreignId('teacher_id')->constrained()->onDelete('restrict');
            $table->string('academic_year'); // e.g., 2024/2025
            $table->integer('semester')->default(1); // 1 or 2
            $table->enum('day', Day::values());
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room');
            $table->timestamps();

            $table->index(['classroom_id', 'day', 'academic_year']);
            $table->index(['teacher_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
