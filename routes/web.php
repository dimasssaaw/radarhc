<?php

use App\Http\Controllers\AssessmentReportController;
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
use App\Http\Controllers\MentoringController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserAssessmentReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserIdpController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserMentoringController;
use App\Http\Controllers\UserTrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [LoginController::class, 'create'])->name('login');
    Route::get('/login', [LoginController::class, 'create']);
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');

    Route::get('employee-data/template', [EmployeeController::class, 'template'])->name('employee.template');
    Route::post('employee-data/import', [EmployeeController::class, 'import'])->name('employee.import');
    Route::resource('assessment-report', AssessmentReportController::class)->names('assessment-report');
    Route::resource('employee-data', EmployeeController::class)->names('employee');

    Route::resource('profile', ProfileController::class)->only(['index', 'show'])->names('profile');

    Route::resource('user-management', UserManagementController::class)->names('user-management');
    Route::resource('user-assessment-report', UserAssessmentReportController::class)->only(['index', 'show'])->names('user-assessment-report');
    Route::resource('user-mentoring', UserMentoringController::class)->only(['index', 'show'])->names('user-mentoring');
    Route::get('user-mentoring/employee/{employee_id}', [UserMentoringController::class, 'showEmployee'])->name('user-mentoring.show-employee');
    Route::resource('user-idp', UserIdpController::class)->only(['index', 'show'])->names('user-idp');
    Route::get('user-idp/employee/{employee_id}', [UserIdpController::class, 'showEmployee'])->name('user-idp.show-employee');
    Route::resource('user-training', UserTrainingController::class)->only(['index', 'show'])->names('user-training');
    Route::resource('user-certification', App\Http\Controllers\UserCertificationController::class)->only(['index', 'show'])->names('user-certification');
    Route::resource('user-journey', App\Http\Controllers\UserJourneyController::class)->only(['index', 'show'])->names('user-journey');
    Route::resource('employee-via-character', EmployeeViaCharacterController::class)->only(['store', 'update', 'destroy'])->names('employee-via-character');
    Route::resource('employee-strength', EmployeeStrengthController::class)->only(['store', 'update', 'destroy'])->names('employee-strength');
    Route::post('employee-scarf/{id_karyawan}', [EmployeeScarfController::class, 'update'])->name('employee-scarf.update');
    Route::resource('departments', DepartmentController::class)->except(['create', 'show'])->names('department');
    Route::resource('positions', PositionController::class)->except(['create', 'show'])->names('position');
    Route::resource('employee-journey', EmployeeJourneyController::class)->only(['index', 'show', 'store', 'update', 'destroy'])->names('employee-journey');
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
    Route::post('idp/{idp}/grow', [IdpController::class, 'updateGrow'])->name('idp.update-grow');
    Route::post('idp/{idp}/penilaian', [IdpController::class, 'updatePenilaian'])->name('idp.update-penilaian');

    Route::post('idp/employee/{employee_id}/info', [IdpController::class, 'updateEmployeeInfo'])->name('idp.update-employee-info');
    Route::post('idp/{idp}/info', [IdpController::class, 'updateInfo'])->name('idp.update-info');

    Route::post('idp/employee/{employee_id}/jadwal', [IdpController::class, 'updateEmployeeJadwal'])->name('idp.update-employee-jadwal');
    Route::post('idp/{idp}/jadwal', [IdpController::class, 'updateJadwal'])->name('idp.update-jadwal');

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
