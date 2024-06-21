<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Simple;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AIController;
use App\Http\Controllers\Api\DrController;
use App\Http\Middleware\CheckEmployeeType;
use App\Http\Controllers\Api\LabController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NurseScheduleAdmin;
use App\Http\Controllers\Api\PharmacyController;
use App\Http\Controllers\Api\RadiologistController;
use App\Http\Controllers\Api\AdminstratorControoler;
use App\Http\Controllers\Api\MedicalImageController;
use App\Http\Controllers\Api\ReciptionistController;
use App\Http\Controllers\Api\AdministratorController;
use App\Http\Middleware\CorsMiddleware;

// Route::get('/data',[Simple::class,'index']);
// Route::get('/example', function (Request $request) {
//     return response()->json(['message' => 'Hello from the Laravel API!']);
// });
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// CORS OPTIONS route handling
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
})->where('any', '.*');

Route::middleware([CorsMiddleware::class])->group(function () {
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/account', [AuthController::class, 'account']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    // Route::post('/xray/predict', [MedicalImageController::class, 'predictXray']);
    // Route::post('/ct/predict', [MedicalImageController::class, 'predictCt']);
});

Route::group(['middleware' => ['api', 'admin']], function () {
    Route::prefix('auth/admin')->group(function () {
        Route::put('/editPatient/{patientId}', [AdminstratorControoler::class, 'editPatient']);
        Route::delete('/deletePatient/{patientId}', [AdminstratorControoler::class, 'deletePatient']);
        Route::post('/addEmployee', [AdminstratorControoler::class, 'addEmployee']);
        Route::delete('/deleteEmployee/{employeeId}', [AdminstratorControoler::class, 'deleteEmployee']);
        Route::put('/editEmployee/{employeeId}', [AdminstratorControoler::class, 'editEmployee']);
        Route::get('/AdminSearchPatient/{nationalId}', [AdminstratorControoler::class, 'AdminSearchPatient']);
        Route::post('/adminstrator', [AuthController::class, 'adminstrator']);
        Route::post('/doctor/reports/{patient_id}', [AdminstratorControoler::class, 'reports']);
        Route::delete('/ExitRequest/{exit_id}', [AdminstratorControoler::class, 'deleteExitRequest']);
        Route::post('/consultation/{patient_id}', [AdminstratorControoler::class, 'consultation']);
        Route::get('/exit-requests', [AdminstratorControoler::class, 'getExitRequests']);
        Route::post('/requestRadiology', [AdminstratorControoler::class, 'requestRadiology']);
        Route::post('/requestMedicine', [AdminstratorControoler::class, 'requestMedicine']);
        Route::post('/requestOxygen', [AdminstratorControoler::class, 'requestOxygen']);
        Route::post('/requestLab', [AdminstratorControoler::class, 'requestLab']);
        Route::post('/labctresult', [MedicalImageController::class, 'labctresult']);
        Route::post('/labXRayResult', [MedicalImageController::class, 'labXRayResult']);
    });
});

Route::group(['middleware' => ['api', 'doctor']], function () {
    Route::prefix('auth/doctor')->group(function () {
        Route::post('/medical_record', [DrController::class, 'patient']);
        Route::post('/reports/{patientId}', [DrController::class, 'add_reports']);
        Route::get('/searchPatient/{patientId}', [DrController::class, 'searchPatient']);
        Route::post('/orderMedicine/{patientId}', [DrController::class, 'orderMedicine']);
        Route::post('/exitOrder/{patientId}', [DrController::class, 'exitOrder']);
        Route::post('/generalOrder/{nurseId}', [DrController::class, 'generalOrder']);
        Route::post('/changeRoom/{patientId}', [DrController::class, 'changeRoom']);
        Route::post('/sendRayOrder/{patientId}', [DrController::class, 'sendRayOrder']);
        Route::get('/medicalrecord/{id}', [DrController::class, 'getPatientData']);
        Route::post('/add_lab_request/{patient_id}', [DrController::class, 'add_lab_request']);
        Route::post('/add_radiology_request/{patient_id}', [DrController::class, 'add_radiology_request']);
        Route::post('/add_consultation_request/{patient_id}', [DrController::class, 'add_consultation_request']);
        Route::post('/requestMedicine', [DrController::class, 'requestMedicine']);
        Route::post('/requestOxygen/{patient_id}', [DrController::class, 'requestOxygen']);
        Route::post('/requestRadiology/{patient_id}', [DrController::class, 'requestRadiology']);
        Route::post('/exitRequest/{patient_id}', [DrController::class, 'exitRequest']);
        Route::get('/getPatient/{patientId}', [DrController::class, 'getPatient']);
        Route::get('/getMedicine', [DrController::class, 'getMedicine']);
        Route::post('/labctresult', [MedicalImageController::class, 'labctresult']);
        Route::post('/labXRayResult', [MedicalImageController::class, 'labXRayResult']);
    });
});

Route::group(['middleware' => ['api', 'receptionist']], function () {
    Route::prefix('auth/receptionist')->group(function () {
        Route::post('/receptionist', [ReciptionistController::class, 'receptionist']);
        Route::get('/searchPatient/{national_id}', [ReciptionistController::class, 'searchPatient']);
        Route::post('/CreatePatient', [ReciptionistController::class, 'CreatePatient']);

    });
});

// Nurse-specific routes
Route::group([
    'middleware' => ['api', 'nurse'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
        Route::post('/nurse', [AuthController::class, 'nurse']);
        Route::post('/nurse', [AuthController::class, 'nurseschedule']);
        Route::get('/nurse/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
        Route::patch('nurse/completedTask/{id}', [NurseScheduleAdmin::class, 'completedTask']);
        Route::post('/nurse/labctresult', [MedicalImageController::class, 'labctresult']);
        Route::post('/nurse/labXRayResult', [MedicalImageController::class, 'labXRayResult']);

        Route::get('/nurse/getNurseAdminRequests', [NurseScheduleAdmin::class, 'getNurseAdminRequests']);

    });
   Route::group([
    'middleware' => ['api', 'nurse-admin'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
    Route::post('/nurse-admin/postNurseTask', [NurseScheduleAdmin::class, 'postNurseTask']);
    Route::get('/nurse-admin/getCompletedTasks', [NurseScheduleAdmin::class, 'getCompletedTasks']);
    Route::get('nurse-admin/oxygen-requests', [NurseScheduleAdmin::class, 'getOxygenRequests']);
    Route::get('/nurse-admin/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
    Route::delete('/nurse-admin/deleteTask/{id}', [NurseScheduleAdmin::class, 'deleteTask']);
    Route::get('nurse-admin/getRequests', [NurseScheduleAdmin::class, 'getRequests']);
    });


// Pharmacy-specific routes
Route::group([
    'middleware' => ['api', 'pharmacy'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
        Route::post('/pharmacy', [PharmacyController::class, 'pharmacy']);
        Route::post('/pharmacist', [PharmacyController::class, 'pharmacist']);
        Route::put('/pharmacy/editMedicine/{Medicine_ID}', [PharmacyController::class, 'editMedicine']);
        Route::get('/pharmacy/getMedicine', [PharmacyController::class, 'getMedicine']);
        Route::post('/pharmacy/addMedicine', [PharmacyController::class, 'addMedicine']);
        Route::delete('/pharmacy/deleteMedicine/{Medicine_ID}', [PharmacyController::class, 'deleteMedicine']);
        Route::get('/pharmacy/searchMedicine', [PharmacyController::class, 'searchMedicine']);
        Route::get('pharmacy/getMedicineRequest', [PharmacyController::class, 'getMedicineRequest']);
    });
    Route::group([
        'middleware' => ['api', 'radiologist'],
        'prefix' => 'auth'
    ], function () {
        Route::patch('/radiologist/completedLabTasks/{id}', [RadiologistController::class, 'completedLabTasks']);
        Route::patch('/radiologist/completedRadiologyTasks/{id}', [RadiologistController::class, 'completedRadiologyTasks']);
        Route::get('/radiologist/getRadiologyRequests', [RadiologistController::class, 'getRadiologyRequests']);
        Route::post('/lab/labctresult', [MedicalImageController::class, 'labctresult']);
        Route::post('/lab/labXRayResult', [MedicalImageController::class, 'labXRayResult']);
    });

    Route::group([
        'middleware' => ['api', 'radiologist-admin'],
        'prefix' => 'auth'
    ], function () {
        Route::get('/radiologist-admin/getRadiologyRequests', [RadiologistController::class, 'getRadiologyRequests']);
        Route::get('/radiologist-admin/getCompletedRadiologyTasks', [RadiologistController::class, 'getCompletedRadiologyTasks']);
        Route::post('/radiologist-admin/radTasks', [RadiologistController::class, 'radTasks']);
        Route::delete('/radiologist-admin/deleteTask/{id}', [RadiologistController::class, 'deleteTask']);
    });

    Route::group([
        'middleware' => ['api', 'lab'],
        'prefix' => 'auth'
    ], function () {
        Route::patch('/lab/completedLabTasks/{id}', [LabController::class, 'completedLabTasks']);
        Route::get('/lab/getLabRequests', [LabController::class, 'getLabRequests']);
        Route::post('/lab/labResult', [LabController::class, 'labResult']);

    });

    Route::group([
        'middleware' => ['api', 'lab-admin'],
        'prefix' => 'auth'
    ], function () {
        Route::get('/lab-admin/getLabRequests', [LabController::class, 'getLabRequests']);
        Route::get('/lab-admin/getCompletedLabTasks', [LabController::class, 'getCompletedLabTasks']);
        Route::post('/lab-admin/labTasks', [LabController::class, 'labTasks']);
        Route::delete('/lab-admin/deleteTask/{id}', [LabController::class, 'deleteTask']);
    });

Route::group(['middleware' => 'checkTokenValidity'], function () {
    // Your routes here
});
});
