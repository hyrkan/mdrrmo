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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('dates');  // Laravel will handle SQLite compatibility
            $table->string('organized_by');
            $table->string('requesting_party')->nullable();
            $table->string('venue')->nullable();
            $table->string('course_facilitator')->nullable();
            $table->string('instructor')->nullable();
            $table->timestamps();  // Automatically NOT NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
