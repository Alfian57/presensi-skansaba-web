<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip')->unique();
            $table->date('date_of_birth');
            $table->enum('gender', Gender::values());
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->index('nip');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
