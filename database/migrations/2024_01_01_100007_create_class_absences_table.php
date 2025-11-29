<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('class_absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('restrict');
            $table->foreignId('schedule_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date');
            $table->text('reason')->nullable();
            $table->string('proof_file')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'date']);
            $table->index(['subject_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_absences');
    }
};
