<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('grade_level')->nullable(); // e.g., 10, 11, 12
            $table->string('major')->nullable(); // e.g., RPL, TKJ, MM
            $table->integer('class_number')->nullable(); // e.g., 1, 2, 3
            $table->integer('capacity')->default(40);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
