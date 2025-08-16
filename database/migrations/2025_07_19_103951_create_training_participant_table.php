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
        Schema::create('training_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->boolean('certificate')->default(false);
            $table->timestamps();
            
            // Prevent duplicate enrollments
            $table->unique(['training_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_participant');
    }
};
