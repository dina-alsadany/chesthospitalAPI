<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Simple;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DrController;
use App\Http\Middleware\CheckEmployeeType;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NurseScheduleAdmin;
use App\Http\Controllers\Api\PharmacyController;
use App\Http\Controllers\Api\AdminstratorControoler;
use App\Http\Controllers\Api\ReciptionistController;
use App\Http\Controllers\Api\AdministratorController;
Route::get('/data',[Simple::class,'index']);

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/account', [AuthController::class, 'account']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
       // Route::post('/employee', [AuthController::class, 'employee']);

});


Route::group([
    'middleware' => ['api', 'admin'], // Apply admin middleware
    'prefix' => 'auth'
], function () {
    Route::put('/admin/editPatient/{patientId}', [AdminstratorControoler::class, 'editPatient']);
    Route::delete('/admin/deletePatient/{patientId}', [AdminstratorControoler::class, 'deletePatient']);
    Route::post('/admin/addEmployee', [AdminstratorControoler::class, 'addEmployee']);
    Route::put('/admin/deleteEmployee/{employeeId}', [AdminstratorControoler::class, 'deleteEmployee']);
    Route::put('/admin/editEmployee/{employeeId}', [AdminstratorControoler::class, 'editEmployee']);
    Route::get('/admin/AdminSearchPatient/{patientId}', [AdminstratorControoler::class, 'AdminSearchPatient']);
    Route::post('/admin/adminstrator', [AuthController::class, 'adminstrator']);
});

Route::group([
    'middleware' => ['api', 'doctor'], // Apply doctor middleware
    'prefix' => 'auth'
], function () {
    Route::post('/doctor/medical_record', [DrController::class, 'patient']);
    Route::post('/doctor/add_reports/{patientId}', [DrController::class, 'add_reports']);
    Route::get('/doctor/searchPatient/{patientId}', [DrController::class, 'searchPatient']);
    Route::post('/doctor/orderMedicine/{patientId}', [DrController::class, 'orderMedicine']);
    Route::post('/doctor/exitOrder/{patientId}', [DrController::class, 'exitOrder']);
    Route::post('/doctor/generalOrder/{nurseId}', [DrController::class, 'generalOrder']);
    Route::post('/doctor/changeRoom/{patientId}', [DrController::class, 'changeRoom']);
    Route::post('/doctor/sendRayOrder/{patientId}', [DrController::class, 'sendRayOrder']);
    Route::get('/doctor/medicalrecord/{id}', [DrController::class, 'getPatientData']);
    //Route::post('/doctor', [AuthController::class, 'doctor']);

    Route::post('/doctor/add_lab_request/{patient_id}', [DrController::class, 'add_lab_request']);

    Route::post('/doctor/add_consultation_request/{patient_id}', [DrController::class, 'add_consultation_request']);

    Route::post('/doctor/requestMedicine/{patient_id}', [DrController::class, 'requestMedicine']);

    Route::post('/doctor/requestOxygen/{patient_id}', [DrController::class, 'requestOxygen']);
    Route::post('/doctor/requestRadiology/{patient_id}', [DrController::class, 'requestRadiology']);

});

Route::group([
    'middleware' => ['api', 'receptionist'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
        Route::post('/receptionist', [ReciptionistController::class, 'receptionist']);
        Route::get('/receptionist/searchPatient', [ReciptionistController::class, 'searchOrCreatePatient']);
        Route::post('/receptionist/CreatePatient', [ReciptionistController::class, 'CreatePatient']);
    });

   // Nurse-specific routes
   Route::group([
    'middleware' => ['api', 'nurse'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
        Route::post('/nurse', [AuthController::class, 'nurse']);
        Route::post('/nurseschedule', [AuthController::class, 'nurseschedule']);
        Route::get('/nurse/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
        Route::get('/NurseSchedule/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
    });


// Pharmacy-specific routes
Route::group([
    'middleware' => ['api', 'pharmacy'], // Apply receptionist middleware
    'prefix' => 'auth'
], function () {
        Route::post('/pharmacy', [PharmacyController::class, 'pharmacy']);
        Route::post('/pharmacist', [PharmacyController::class, 'pharmacist']);
        Route::put('/pharmacy/editMedicine/{Medicine_ID}', [PharmacyController::class, 'editMedicine']);
    });

Route::group(['middleware' => 'checkTokenValidity'], function () {
    // Your routes here
});
    // Route::post('/employee', [AuthController::class, 'employee']);


   // Route::post('/doctor/patient', [AuthController::class, 'patient']);
    // Route::post('/medical_record', [AuthController::class, 'medical_record']);
    // Route::post('/raylab', [AuthController::class, 'raylab']);
    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/pharmacy', [PharmacyController::class, 'pharmacy']);
    // Route::post('/pharmacist', [PharmacyController::class, 'pharmacist']);
    // Route::post('/prescribe', [AuthController::class, 'prescribe']);
    // Route::post('/doctor/dr_reports', [DrController::class, 'dr_reports']);
//     Route::post('/doctor', [AuthController::class, 'doctor']);
//     Route::post('/nurse', [AuthController::class, 'nurse']);
//     Route::post('/nurseschedule', [AuthController::class, 'nurseschedule']);
//     Route::post('/receptionist', [ReciptionistController::class, 'receptionist']);
//      Route::get('/doctor/searchPatient/{patientId}', [DrController::class, 'searchPatient']);
//      Route::post('/doctor/orderMedicine/{patientId}', [DrController::class, 'orderMedicine']);
//      Route::post('/doctor/exitOrder/{patientId}', [DrController::class, 'exitOrder']);
//     Route::post('/doctor/generalOrder/{nurseId}', [DrController::class, 'generalOrder']);
//     Route::post('/doctor/changeRoom/{patientId}', [DrController::class, 'changeRoom']);
//     Route::post('/doctor/sendRayOrder/{patientId}', [DrController::class, 'sendRayOrder']);

//     Route::get('/receptionist/searchOrCreatePatient', [ReciptionistController::class, 'searchOrCreatePatient']);
//     Route::post('/receptionist/CreatePatient', [ReciptionistController::class, 'CreatePatient']);



//     Route::get('/nurse/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);




//     Route::get('/NurseSchedule/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);


//     Route::post('/doctor/medicine', [PharmacyController::class, 'medicine']);


//     Route::put('/doctor/editMedicine/{Medicine_ID}', [PharmacyController::class, 'editMedicine']);
//     Route::put('/pharmacy/editMedicine/{Medicine_ID}', [PharmacyController::class, 'editMedicine']);

//     Route::get('doctor/patient/{id}', [DrController::class, 'getPatientData']);


// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::put('/admintrator/editPatient/{patientId}', [AdminstratorControoler::class, 'editPatient']);
//     Route::put('/admintrator/deletePatient/{patientId}', [AdminstratorControoler::class, 'deletePatient']);
//     Route::post('/admintrator/addEmployee', [AdminstratorControoler::class, 'addEmployee']);
//     Route::put('/admintrator/deleteEmployee/{patientId}', [AdminstratorControoler::class, 'deleteEmployee']);
//     Route::put('/admintrator/editEmployee/{patientId}', [AdminstratorControoler::class, 'editEmployee']);
//     Route::post('/adminstrator', [AuthController::class, 'adminstrator']);
//     Route::get('/admintrator/AdminSearchPatient/{patientId}', [AdminstratorControoler::class, 'AdminSearchPatient']);
// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/employee', [AuthController::class, 'employee']);
//     Route::post('/account', [AuthController::class, 'account']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::get('/user-profile', [AuthController::class, 'userProfile']);

    // Route::group(['middleware' => 'role:doctor'], function () {
    //     Route::post('/doctor/patient', [AuthController::class, 'patient']);
    //     Route::post('/doctor/dr_reports', [DrController::class, 'dr_reports']);
    //     Route::get('/doctor/searchPatient/{patientId}', [DrController::class, 'searchPatient']);
    //     Route::post('/doctor/orderMedicine/{patientId}', [DrController::class, 'orderMedicine']);
    //     Route::post('/doctor/exitOrder/{patientId}', [DrController::class, 'exitOrder']);
    //     Route::post('/doctor/generalOrder/{nurseId}', [DrController::class, 'generalOrder']);
    //     Route::post('/doctor/changeRoom/{patientId}', [DrController::class, 'changeRoom']);
    //     Route::post('/doctor/sendRayOrder/{patientId}', [DrController::class, 'sendRayOrder']);
    //     Route::get('/doctor/patient/{id}', [DrController::class, 'getPatientData']);
    // });
//     // Administrator Routes
//     Route::group(['middleware' => 'role:administrator'], function () {
//         Route::put('/admin/editPatient/{patientId}', [AdministratorController::class, 'editPatient']);
//         Route::delete('/admin/deletePatient/{patientId}', [AdministratorController::class, 'deletePatient']);
//         Route::post('/admin/addEmployee', [AdministratorController::class, 'addEmployee']);
//         Route::put('/admin/deleteEmployee/{employeeId}', [AdministratorController::class, 'deleteEmployee']);
//         Route::put('/admin/editEmployee/{employeeId}', [AdministratorController::class, 'editEmployee']);
//         Route::get('/admin/AdminSearchPatient/{patientId}', [AdministratorController::class, 'AdminSearchPatient']);
//     });


//     // Nurse-specific routes
//     Route::group(['middleware' => 'role:nurse'], function () {
//         Route::post('/nurse', [AuthController::class, 'nurse']);
//         Route::post('/nurseschedule', [AuthController::class, 'nurseschedule']);
//         Route::get('/nurse/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
//         Route::get('/NurseSchedule/NurseSearchPatient/{patientId}', [NurseScheduleAdmin::class, 'NurseSearchPatient']);
//     });

//     // Receptionist-specific routes
//     Route::group(['middleware' => 'role:receptionist'], function () {
//         Route::post('/receptionist', [ReciptionistController::class, 'receptionist']);
//         Route::get('/receptionist/searchOrCreatePatient', [ReciptionistController::class, 'searchOrCreatePatient']);
//         Route::post('/receptionist/CreatePatient', [ReciptionistController::class, 'CreatePatient']);
//     });


//     // Pharmacy-specific routes
//     Route::group(['middleware' => 'role:pharmacy'], function () {
//         Route::post('/pharmacy', [PharmacyController::class, 'pharmacy']);
//         Route::post('/pharmacist', [PharmacyController::class, 'pharmacist']);
//         Route::put('/pharmacy/editMedicine/{Medicine_ID}', [PharmacyController::class, 'editMedicine']);
//     });
// });
