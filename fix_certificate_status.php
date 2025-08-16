<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\DB;

// Check current state
echo "Checking current certificate status...\n";

$participants = DB::table('training_participant')
    ->select('id', 'training_id', 'participant_id', 'completion_status', 'certificate', 'certificate_serial', 'issued_by')
    ->get();

echo "Total participants: " . $participants->count() . "\n";

$withCertificateTrue = $participants->where('certificate', true);
echo "Participants with certificate=true: " . $withCertificateTrue->count() . "\n";

$withSerials = $participants->whereNotNull('certificate_serial');
echo "Participants with certificate serials: " . $withSerials->count() . "\n";

$completed = $participants->where('completion_status', 'completed');
echo "Participants with completed status: " . $completed->count() . "\n";

// Find problematic records (certificate=true but no serial)
$problematic = $participants->where('certificate', true)->whereNull('certificate_serial');
echo "Problematic records (certificate=true but no serial): " . $problematic->count() . "\n";

if ($problematic->count() > 0) {
    echo "Fixing problematic records...\n";
    foreach ($problematic as $record) {
        DB::table('training_participant')
            ->where('training_id', $record->training_id)
            ->where('participant_id', $record->participant_id)
            ->update(['certificate' => false]);
    }
    echo "Fixed " . $problematic->count() . " records.\n";
}

echo "Done.\n";
