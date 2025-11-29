<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('restrict');
            $table->string('nisn')->unique();
            $table->string('nis')->unique();
            $table->date('date_of_birth');
            $table->enum('gender', Gender::values());
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->year('entry_year');
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('active_device_id')->nullable();
            $table->timestamp('device_registered_at')->nullable();
            $table->timestamps();

            $table->index('nisn');
            $table->index('nis');
            $table->index(['classroom_id', 'entry_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
