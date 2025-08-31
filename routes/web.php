<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::resource('trainings', App\Http\Controllers\TrainingController::class);
    Route::get('trainings/{training}/participants', [App\Http\Controllers\TrainingController::class, 'participants'])
        ->name('trainings.participants');
    Route::get('trainings/{training}/enrolled-participants', [App\Http\Controllers\TrainingController::class, 'enrolledParticipants'])
        ->name('trainings.enrolled-participants');
    Route::get('trainings/{training}/export-participants', [App\Http\Controllers\TrainingController::class, 'exportEnrolledParticipants'])
        ->name('trainings.export-participants');
    Route::post('trainings/{training}/participants', [App\Http\Controllers\TrainingController::class, 'updateParticipants'])
        ->name('trainings.participants.update');
    Route::post('trainings/{training}/participants/store', [App\Http\Controllers\TrainingController::class, 'storeParticipant'])
        ->name('trainings.participants.store');
    Route::put('trainings/{training}/participants/{participant}/status', [App\Http\Controllers\TrainingController::class, 'updateParticipantStatus'])
        ->name('trainings.participants.update-status');
    Route::post('trainings/{training}/participants/bulk-status', [App\Http\Controllers\TrainingController::class, 'bulkUpdateParticipantStatus'])
        ->name('trainings.participants.bulk-status');
    Route::post('trainings/{training}/participants/bulk-certificates', [App\Http\Controllers\TrainingController::class, 'bulkAssignCertificateSerials'])
        ->name('trainings.participants.bulk-certificates');
    Route::get('api/participants-by-organization', [App\Http\Controllers\TrainingController::class, 'getParticipantsByOrganization'])
        ->name('api.participants.by-organization');
        
    Route::resource('participants', App\Http\Controllers\ParticipantController::class);
    Volt::route('register', 'auth.register')->name('register');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    // Add this route with the other training participant routes
    Route::post('/trainings/{training}/participants/upload-excel', [App\Http\Controllers\TrainingController::class, 'uploadExcelParticipants'])
        ->name('trainings.participants.upload-excel');
});

require __DIR__.'/auth.php';
