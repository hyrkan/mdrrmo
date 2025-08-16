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
        Schema::table('training_participant', function (Blueprint $table) {
            $table->enum('completion_status', ['enrolled', 'completed', 'did_not_complete'])->default('enrolled')->after('certificate');
            $table->timestamp('completed_at')->nullable()->after('completion_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_participant', function (Blueprint $table) {
            $table->dropColumn(['completion_status', 'completed_at']);
        });
    }
};
