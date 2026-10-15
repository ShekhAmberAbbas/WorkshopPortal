<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CertificateTemplateController;
use App\Http\Controllers\Admin\AcademicOptionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\WorkshopController;
use App\Http\Controllers\Admin\ParticipantDirectoryController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\WorkshopRegistrationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('workshops', WorkshopController::class);
    Route::post('workshops/{workshop}/toggle-status', [WorkshopController::class, 'toggleStatus'])
        ->name('workshops.toggle-status');
    Route::get('workshops/{workshop}/qr/image', [WorkshopController::class, 'qrImage'])
        ->name('workshops.qr.image');
    Route::get('workshops/{workshop}/qr/download', [WorkshopController::class, 'downloadQr'])
        ->name('workshops.qr.download');
    Route::get('workshops/{workshop}/qr/print', [WorkshopController::class, 'printQr'])
        ->name('workshops.qr.print');

    Route::get('workshops/{workshop}/participants', [ParticipantController::class, 'index'])
        ->name('workshops.participants.index');
    Route::get('workshops/{workshop}/participants/export', [ParticipantController::class, 'export'])
        ->name('workshops.participants.export');
    Route::delete('workshops/{workshop}/participants/{participant}', [ParticipantController::class, 'destroy'])
        ->name('workshops.participants.destroy');
    Route::post('workshops/{workshop}/participants/{participant}/certificate', [ParticipantController::class, 'generateCertificate'])
        ->name('workshops.participants.certificate.generate');
    Route::get('workshops/{workshop}/participants/{participant}/certificate/download', [ParticipantController::class, 'downloadCertificate'])
        ->name('workshops.participants.certificate.download');

    Route::get('workshops/{workshop}/certificates', [CertificateController::class, 'index'])
        ->name('workshops.certificates.index');
    Route::post('workshops/{workshop}/certificates/bulk-generate', [CertificateController::class, 'bulkGenerate'])
        ->name('workshops.certificates.bulk-generate');
    Route::post('workshops/{workshop}/certificates/bulk-generate-selected', [CertificateController::class, 'bulkGenerateSelected'])
        ->name('workshops.certificates.bulk-generate-selected');
    Route::get('workshops/{workshop}/certificates/export', [CertificateController::class, 'export'])
        ->name('workshops.certificates.export');

    Route::post('certificates/{certificate}/revoke', [CertificateController::class, 'revoke'])
        ->name('certificates.revoke');
    Route::post('certificates/{certificate}/reactivate', [CertificateController::class, 'reactivate'])
        ->name('certificates.reactivate');
    Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])
        ->name('certificates.download');


    Route::match(['get', 'post'], 'participants', [ParticipantDirectoryController::class, 'index'])
    ->name('participants.index');

// Export intentionally uses POST because filters are submitted in the request/session.
Route::post('participants/export', [ParticipantDirectoryController::class, 'export'])
    ->name('participants.export');

Route::get('participants/student/{identifier}', [ParticipantDirectoryController::class, 'student'])
    ->name('participants.student');


Route::get('participants/{participant}', [ParticipantDirectoryController::class, 'show'])
    ->name('participants.show');

    Route::get('academic-options', [AcademicOptionController::class, 'index'])->name('academic.index');
    Route::post('academic-options/courses', [AcademicOptionController::class, 'storeCourse'])->name('academic.courses.store');
    Route::patch('academic-options/courses/{course}', [AcademicOptionController::class, 'updateCourse'])->name('academic.courses.update');
    Route::post('academic-options/courses/{course}/toggle', [AcademicOptionController::class, 'toggleCourse'])->name('academic.courses.toggle');
    Route::post('academic-options/branches', [AcademicOptionController::class, 'storeBranch'])->name('academic.branches.store');
    Route::patch('academic-options/branches/{branch}', [AcademicOptionController::class, 'updateBranch'])->name('academic.branches.update');
    Route::post('academic-options/branches/{branch}/toggle', [AcademicOptionController::class, 'toggleBranch'])->name('academic.branches.toggle');

    Route::resource('certificate-templates', CertificateTemplateController::class)->except(['show']);
    Route::get('certificate-templates/{certificateTemplate}/preview', [CertificateTemplateController::class, 'preview'])
        ->name('certificate-templates.preview');
});

// Public, no auth -- students reach these via QR scan or shared link.
// Registration is throttled tighter than verification: registration writes
// to the DB per hit, verification/download are read-mostly and meant to be
// checked widely (e.g. by an employer scanning a printed certificate).
Route::middleware(['throttle:20,1'])->group(function () {
    Route::get('/workshop/{workshop}/register', [WorkshopRegistrationController::class, 'show'])
        ->name('workshop.register.show');
    Route::get('/workshop/courses/{course}/branches', [WorkshopRegistrationController::class, 'branches'])->name('workshop.courses.branches');
    Route::post('/workshop/{workshop}/register', [WorkshopRegistrationController::class, 'store'])
        ->name('workshop.register.store');
    Route::get('/workshop/{workshop}/register/success/{participant}', [WorkshopRegistrationController::class, 'success'])
        ->name('workshop.register.success');
});

Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/certificate/verify/{certificate}', [CertificateVerificationController::class, 'show'])
        ->name('certificate.verify');
    Route::get('/certificate/download/{certificate}', [CertificateVerificationController::class, 'download'])
        ->name('certificate.download');
});

require __DIR__ . '/auth.php';
