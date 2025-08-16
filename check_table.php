<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use Illuminate\Support\Facades\DB;

echo "training_participant table structure:\n";
$columns = DB::select('PRAGMA table_info(training_participant)');
foreach ($columns as $column) {
    echo "- {$column->name} ({$column->type})\n";
}
