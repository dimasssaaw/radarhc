<?php

use App\Http\Controllers\AssessmentReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeJourneyController;
use App\Http\Controllers\EmployeeScarfController;
use App\Http\Controllers\EmployeeStrengthController;
use App\Http\Controllers\EmployeeViaCharacterController;
use App\Http\Controllers\IdpController;
use App\Http\Controllers\MasterAssessmentController;
use App\Http\Controllers\MasterIdpController;
use App\Http\Controllers\MentoringController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserAssessmentReportController;
use App\Http\Controllers\UserCertificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserIdpController;
use App\Http\Controllers\UserJourneyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserMentoringController;
use App\Http\Controllers\UserTrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [LoginController::class, 'create'])->name('login');
    Route::get('/login', [LoginController::class, 'create']);
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email')->middleware('throttle:5,1');
});

// Serve public HRG files (Training & Certification attachments, employee photos, etc.)
Route::get('/HRG/{path}', function (string $path) {
    $cleanPath = str_replace(['../', '..\\'], '', $path);
    $fullPath = storage_path('app/public/HRG/'.$cleanPath);

    if (! file_exists($fullPath) || is_dir($fullPath)) {
        // Fallback for directory casing differences on Linux/Windows (e.g. Training vs training)
        $segments = explode('/', $cleanPath);
        if (! empty($segments)) {
            $segments[0] = ucfirst($segments[0]);
            $altPath = storage_path('app/public/HRG/'.implode('/', $segments));
            if (file_exists($altPath) && ! is_dir($altPath)) {
                $fullPath = $altPath;
            } else {
                $segments[0] = strtolower($segments[0]);
                $altPath2 = storage_path('app/public/HRG/'.implode('/', $segments));
                if (file_exists($altPath2) && ! is_dir($altPath2)) {
                    $fullPath = $altPath2;
                }
            }
        }
    }

    if (! file_exists($fullPath) || is_dir($fullPath)) {
        abort(404, 'File not found');
    }

    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];

    $contentType = $mimeTypes[$extension] ?? (mime_content_type($fullPath) ?: 'application/octet-stream');

    return response()->file($fullPath, [
        'Content-Type' => $contentType,
        'Content-Disposition' => 'inline; filename="'.basename($fullPath).'"',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('hrg.file');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');

    Route::get('employee-data/template', [EmployeeController::class, 'template'])->name('employee.template');
    Route::post('employee-data/import', [EmployeeController::class, 'import'])->name('employee.import');
    Route::get('assessment-report/employee/{employee_id}', [AssessmentReportController::class, 'showEmployee'])->name('assessment-report.show-employee');
    Route::post('assessment-report/{id}/selected-charts', [AssessmentReportController::class, 'updateSelectedCharts'])->name('assessment-report.update-selected-charts');
    Route::resource('assessment-report', AssessmentReportController::class)->names('assessment-report');
    Route::resource('employee-data', EmployeeController::class)->names('employee');

    Route::resource('profile', ProfileController::class)->only(['index', 'show'])->names('profile');

    Route::resource('user-management', UserManagementController::class)->names('user-management');
    Route::resource('user-assessment-report', UserAssessmentReportController::class)->only(['index', 'show'])->names('user-assessment-report');
    Route::get('user-assessment-report/employee/{employee_id}', [UserAssessmentReportController::class, 'showEmployee'])->name('user-assessment-report.show-employee');
    Route::resource('user-mentoring', UserMentoringController::class)->only(['index', 'show'])->names('user-mentoring');
    Route::get('user-mentoring/employee/{employee_id}', [UserMentoringController::class, 'showEmployee'])->name('user-mentoring.show-employee');
    Route::resource('user-idp', UserIdpController::class)->only(['index', 'show'])->names('user-idp');
    Route::get('user-idp/employee/{employee_id}', [UserIdpController::class, 'showEmployee'])->name('user-idp.show-employee');
    Route::resource('user-training', UserTrainingController::class)->only(['index', 'show'])->names('user-training');
    Route::resource('user-certification', UserCertificationController::class)->only(['index', 'show'])->names('user-certification');
    Route::resource('user-journey', UserJourneyController::class)->only(['index', 'show'])->names('user-journey');
    Route::get('user-journey/{id}/pdf', [UserJourneyController::class, 'exportPdf'])->name('user-journey.pdf');
    Route::resource('employee-via-character', EmployeeViaCharacterController::class)->only(['store', 'update', 'destroy'])->names('employee-via-character');
    Route::resource('employee-strength', EmployeeStrengthController::class)->only(['store', 'update', 'destroy'])->names('employee-strength');
    Route::post('employee-scarf/{id_karyawan}', [EmployeeScarfController::class, 'update'])->name('employee-scarf.update');
    Route::resource('departments', DepartmentController::class)->except(['create', 'show'])->names('department');
    Route::resource('positions', PositionController::class)->except(['create', 'show'])->names('position');

    // Master IDP Routes
    Route::get('master-idp', [MasterIdpController::class, 'index'])->name('master-idp.index');
    Route::post('master-idp/group', [MasterIdpController::class, 'storeGroup'])->name('master-idp.group.store');
    Route::post('master-idp/group/copy', [MasterIdpController::class, 'copyGroup'])->name('master-idp.group.copy');
    Route::put('master-idp/group/{id}', [MasterIdpController::class, 'updateGroup'])->name('master-idp.group.update');
    Route::delete('master-idp/group/{id}', [MasterIdpController::class, 'destroyGroup'])->name('master-idp.group.destroy');
    Route::post('master-idp/group/{groupId}/competency', [MasterIdpController::class, 'storeCompetency'])->name('master-idp.competency.store');
    Route::put('master-idp/competency/{id}', [MasterIdpController::class, 'updateCompetency'])->name('master-idp.competency.update');
    Route::delete('master-idp/competency/{id}', [MasterIdpController::class, 'destroyCompetency'])->name('master-idp.competency.destroy');

    // Master Assessment Routes
    Route::get('master-assessment', [MasterAssessmentController::class, 'index'])->name('master-assessment.index');
    Route::post('master-assessment/category', [MasterAssessmentController::class, 'storeCategory'])->name('master-assessment.category.store');
    Route::put('master-assessment/category/{id}', [MasterAssessmentController::class, 'updateCategory'])->name('master-assessment.category.update');
    Route::delete('master-assessment/category/{id}', [MasterAssessmentController::class, 'destroyCategory'])->name('master-assessment.category.destroy');
    Route::post('master-assessment/category/{categoryId}/aspect', [MasterAssessmentController::class, 'storeAspect'])->name('master-assessment.aspect.store');
    Route::put('master-assessment/aspect/{id}', [MasterAssessmentController::class, 'updateAspect'])->name('master-assessment.aspect.update');
    Route::delete('master-assessment/aspect/{id}', [MasterAssessmentController::class, 'destroyAspect'])->name('master-assessment.aspect.destroy');

    Route::resource('employee-journey', EmployeeJourneyController::class)->only(['index', 'show', 'store', 'update', 'destroy'])->names('employee-journey');
    Route::get('employee-journey/{id}/pdf', [EmployeeJourneyController::class, 'exportPdf'])->name('employee-journey.pdf');
    Route::post('employee-journey/{employee_id}/recommendation', [EmployeeJourneyController::class, 'storeRecommendation'])->name('employee-journey.recommendation');
    Route::get('mentoring/employee/{employee_id}', [MentoringController::class, 'showEmployee'])->name('mentoring.show-employee');
    Route::post('mentoring/employee/{employee_id}/metrics', [MentoringController::class, 'updateEmployeeMetrics'])->name('mentoring.update-employee-metrics');
    Route::post('mentoring/{mentoring}/metrics', [MentoringController::class, 'updateMetrics'])->name('mentoring.update-metrics');
    Route::post('mentoring/employee/{employee_id}/grow', [MentoringController::class, 'updateEmployeeGrow'])->name('mentoring.update-employee-grow');
    Route::post('mentoring/{mentoring}/grow', [MentoringController::class, 'updateGrow'])->name('mentoring.update-grow');

    Route::post('mentoring/employee/{employee_id}/info', [MentoringController::class, 'updateEmployeeInfo'])->name('mentoring.update-employee-info');
    Route::post('mentoring/{mentoring}/info', [MentoringController::class, 'updateInfo'])->name('mentoring.update-info');

    Route::post('mentoring/employee/{employee_id}/progress', [MentoringController::class, 'updateEmployeeProgress'])->name('mentoring.update-employee-progress');
    Route::post('mentoring/{mentoring}/progress', [MentoringController::class, 'updateProgress'])->name('mentoring.update-progress');

    Route::resource('mentoring', MentoringController::class)->names('mentoring');

    Route::get('idp/employee/{employee_id}', [IdpController::class, 'showEmployee'])->name('idp.show-employee');
    Route::post('idp/employee/{employee_id}/metrics', [IdpController::class, 'updateEmployeeMetrics'])->name('idp.update-employee-metrics');
    Route::post('idp/{idp}/metrics', [IdpController::class, 'updateMetrics'])->name('idp.update-metrics');
    Route::post('idp/employee/{employee_id}/grow', [IdpController::class, 'updateEmployeeGrow'])->name('idp.update-employee-grow');
    Route::post('idp/employee/{employee_id}/penilaian', [IdpController::class, 'updateEmployeePenilaian'])->name('idp.update-employee-penilaian');
    Route::post('idp/employee/{employee_id}/competency-scores', [IdpController::class, 'updateEmployeeCompetencyScores'])->name('idp.update-employee-competency-scores');
    Route::post('idp/{idp}/grow', [IdpController::class, 'updateGrow'])->name('idp.update-grow');
    Route::post('idp/{idp}/penilaian', [IdpController::class, 'updatePenilaian'])->name('idp.update-penilaian');
    Route::post('idp/{idp}/competency-scores', [IdpController::class, 'updateCompetencyScores'])->name('idp.update-competency-scores');

    Route::post('idp/employee/{employee_id}/info', [IdpController::class, 'updateEmployeeInfo'])->name('idp.update-employee-info');
    Route::post('idp/{idp}/info', [IdpController::class, 'updateInfo'])->name('idp.update-info');

    Route::post('idp/employee/{employee_id}/jadwal', [IdpController::class, 'updateEmployeeJadwal'])->name('idp.update-employee-jadwal');
    Route::post('idp/{idp}/jadwal', [IdpController::class, 'updateJadwal'])->name('idp.update-jadwal');

    Route::post('idp/{id}/selected-charts', [IdpController::class, 'updateSelectedCharts'])->name('idp.update-selected-charts');
    Route::resource('idp', IdpController::class)->names('idp');
    Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
    Route::get('/training/{id}', [TrainingController::class, 'show'])->name('training.show');
    Route::post('/training/{id}/target', [TrainingController::class, 'updateTarget'])->name('training.update-target');

    // Training History Routes
    Route::post('/training/{employee_id}/history', [TrainingController::class, 'storeHistory'])->name('training.store-history');
    Route::put('/training/history/{id}', [TrainingController::class, 'updateHistory'])->name('training.update-history');
    Route::patch('/training/history/{id}/status', [TrainingController::class, 'updateStatus'])->name('training.update-status');
    Route::delete('/training/history/{id}', [TrainingController::class, 'destroyHistory'])->name('training.destroy-history');
    Route::post('/training/history/{id}/upload-eva', [TrainingController::class, 'uploadEva'])->name('training.upload-eva');

    // Certification Routes
    Route::get('/certification', [CertificationController::class, 'index'])->name('certification.index');
    Route::get('/certification/{id}', [CertificationController::class, 'show'])->name('certification.show');
    Route::post('/certification/{employee_id}', [CertificationController::class, 'store'])->name('certification.store');
    Route::put('/certification/{id}', [CertificationController::class, 'update'])->name('certification.update');
    Route::delete('/certification/{id}', [CertificationController::class, 'destroy'])->name('certification.destroy');
    Route::post('/certification/{id}/upload-lampiran', [CertificationController::class, 'uploadLampiran'])->name('certification.upload-lampiran');

    Route::resource('role', RoleController::class)->names('role');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
