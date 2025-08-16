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
            $table->string('certificate_serial')->nullable()->after('certificate');
            $table->string('issued_by')->nullable()->after('certificate_serial');
            $table->timestamp('certificate_issued_at')->nullable()->after('issued_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_participant', function (Blueprint $table) {
            $table->dropColumn(['certificate_serial', 'issued_by', 'certificate_issued_at']);
        });
    }
};
