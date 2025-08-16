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
        Schema::create('participants', function (Blueprint $table) {
            $table->id(); // INTEGER PRIMARY KEY AUTOINCREMENT
            
            $table->string('id_no')->nullable()->comment('Government/employee ID');
            $table->string('first_name'); // NOT NULL by default in Laravel
            $table->string('middle_name')->nullable(); // Was missing ->nullable()
            $table->string('last_name'); 
            
            // These should be nullable (not all participants may have these)
            $table->string('agency_organization')->nullable()->comment('Employer/affiliation');
            $table->string('position_designation')->nullable()->comment('Job title');
            
            $table->enum('sex', ['male', 'female']); // Correct enum
            
            // Laravel's ->json() automatically handles SQLite compatibility
            $table->json('vulnerable_groups')->nullable()->comment('Array of groups e.g. ["PWD", "Senior"]');
            
            $table->timestamps(); // created_at + updated_at, NOT NULL by default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
