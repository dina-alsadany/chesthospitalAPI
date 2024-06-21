<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Models\oxygen;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Medicine;
use App\Models\DrReports;
use App\Models\radiology;
use App\Models\LabRequest;
use App\Models\ExitRequest;
use App\Models\Adminstrator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\medicinerequests;
use App\Models\ConsultationRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminstratorControoler extends Controller
{
    /**
    * Create a new AuthController instance.
    *
    * @return void
    */

      /**
 * Edit patient data.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $patientId
 * @return \Illuminate\Http\JsonResponse
 */
public function editPatient(Request $request, $patientId)
{
     // Find the patient by ID
     $patient = Patient::find($patientId);

     if (!$patient) {
         return response()->json([
             'status' => 'fail',
             'message' => 'Patient not found',
         ], 404);
     }

     // Update patient data with the fields provided in the request
     $patient->update($request->only([
         'F_Name',
         'L_Name',
         'Phone_Number',
         'City',
         'Street',
         'Email',
         'AccHome',
         'Accwork',
         'Accstreet',
         'Medical_History',
         'hos_ID',
         'DoctorID',
         'NurseID',
         'MR_ID',
     ]));

     return response()->json([
         'status' => 'success',
         'data' => [
             'message' => 'Patient data updated successfully',
             'patient' => $patient,
         ],
     ]);
 }
/**
 * Delete patient data.
 *
 * @param  int  $patientId
 * @return \Illuminate\Http\JsonResponse
 */
public function deletePatient($patientId)
{
    // Find the patient by ID
    $patient = Patient::find($patientId);

    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Delete patient
    $patient->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Patient deleted successfully',
    ]);
}

/**
 * Add a new employee.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'address' => 'required|string|min:6',
            'phone' => [
                'required',
                'string',
                'min:11',
                Rule::unique('employee')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('employee')->where(function ($query) use ($request) {
                    return $query->where('email', $request->email);
                }),
            ],
            'specialization' => 'required|string|between:2,100', // This maps to EmployeeType
            'shift' => 'required|string|between:2,100',
            'birthday' => 'required|date_format:Y-m-d', // corrected to match your JSON field
            'password' => 'required|string|between:2,100',
          //  'role'=> 'required|string|between:2,100',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }
        // $role = strtoupper($request->role);
        // $specialization = strtoupper($request->specialization);
        // Create the new employee
        $employee = Employee::create([
            'Name' => $request->name,
            'Address' => $request->address,
            'Phone' => $request->phone,
            'Email' => $request->email,
            'EmployeeType' => $request->specialization, // Map specialization to EmployeeType
            'shift' => $request->shift,
            'BIRTHDAY' => $request->birthday, // corrected to match your JSON field
            'password' => bcrypt($request->password),
            //'specialization' => $request->specialization,
           // 'EmployeeType' => $request->role, // Map specialization to EmployeeType

        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Employee added successfully',
                'employee' => $employee,
            ],
        ], 201);
    }

/**
 * Delete an employee.
 *
 * @param  int  $employeeId
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteEmployee($employeeId)
{
    // Find the employee by ID
    $employee = Employee::find($employeeId);

    if (!$employee) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Employee not found',
        ], 404);
    }

    // Soft delete the employee
    $employee->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Employee deleted successfully',
    ]);
}

     /**
 * Edit Employee data.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $patientId
 * @return \Illuminate\Http\JsonResponse
 */
public function editEmployee(Request $request, $EmployeeID)
{
     // Find the patient by ID
     $employee = Employee::find($EmployeeID);

     if (!$employee) {
         return response()->json([
             'status' => 'fail',
             'message' => 'employee not found',
         ], 404);
     }

     // Update employee data with the fields provided in the request
     $employee->update($request->only([
         'Name' => $request->name,
            'Address' => $request->address,
            'Phone' => $request->phone,
            'Email' => $request->email,
            'EmployeeType' => $request->specialization, // Map specialization to EmployeeType
            'shift' => $request->shift,
            'BIRTHDAY' => $request->birthday, // corrected to match your JSON field
            'password' => bcrypt($request->password)
     ]));
     $employee->makeHidden(["role_id",
     "is_admin",
     "is_doctor",
     "is_receptionist",
     "is_nurse",
     "is_pharmacy"]);
     return response()->json([
         'status' => 'success',
         'data' => [
             'message' => 'employee data updated successfully',
             'employee' => $employee,
         ],
     ]);
 }
 public function AdminSearchPatient($nationalId)
 {

     // Search by national_id
     $patient = Patient::where('national_id', $nationalId)->first();

     if (!$patient) {
         return response()->json([
             'status' => 'fail',
             'message' => 'Patient not found',
         ], 404);
     }

     // Hide unnecessary fields
     $patient->makeHidden(['hos_ID', 'DoctorID', 'NurseID', 'MR_ID']);

     return response()->json([
         'status' => 'success',
         'data' => $patient,
     ]);
 }
 public function reports(Request $request, $patient_id)
{
    // Validate incoming request data
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'prescription' => 'required|string|max:255', // Assuming 'prescription' corresponds to 'RDescription'
        'diagnose' => 'required|string|max:255',     // Assuming 'diagnose' corresponds to 'diagnoseillness'
        'doctorId' => 'required|exists:employee,EmployeeID', // Assuming 'doctorId' references 'id' in 'employees' table
    ]);
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Create a new report
    $report = DrReports::create([
        'RDescription' => $request->prescription,   // Map 'prescription' to 'RDescription'
        'diagnoseillness' => $request->diagnose,   // Map 'diagnose' to 'diagnoseillness'
        'DoctorID' => $request->doctorId,
        'patient_id' => $patient_id,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Report created successfully',
            'report' => $report,
        ],
    ], 201);
}

/**
 * Delete an exit request.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteExitRequest($exit_id)
{
    // Find the exit request by ID
    $exitRequest = ExitRequest::find($exit_id);

    if (!$exitRequest) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Exit request not found',
        ], 404);
    }

    // Delete the exit request
    $exitRequest->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Exit request deleted successfully',
    ]);
}

    public function consultation(Request $request, $patient_id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'hospital' => 'required|string|max:100',
            'doctorId' => 'required|exists:employee,EmployeeID', // Assuming 'employees' is the correct table name
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create a new consultation request
        $consultation = ConsultationRequest::create([
            'doctorId' => $request->doctorId,
            'patientId' => $patient_id,
            'name' => $request->name,
            'specialization' => $request->specialization,
            'hospital' => $request->hospital,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Consultation request sent successfully',
                'consultation' => $consultation,
            ],
        ], 201);
    }

/**
 * Get all exit requests.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function getExitRequests()
{
    // Retrieve all exit requests
    $exitRequests = ExitRequest::all();

    if ($exitRequests->isEmpty()) {
        return response()->json([
            'status' => 'fail',
            'message' => 'No exit requests found',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $exitRequests,
    ]);
}
/**
     * Request radiology procedure for a patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $patient_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestRadiology(Request $request)
    {
        // Validate incoming request data

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'doctorId' => 'required|exists:employee,EmployeeID', // Assuming 'employees' is the correct table name
            'patientId' => 'required|exists:patient,Pat_ID', // Assuming 'employees' is the correct table name

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        /// Create a new radiology request
    $radiology = radiology::create([
        'doctor_id' => $request->doctorId,
        'patient_id' => $request->patientId,
        'radiology_name' => $request->name,
        'description' => $request->description,
    ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Radiology request created successfully',
                'radiology' => $radiology,
            ],
        ], 201);
    }
    /**
 * Request medicine for a patient.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function requestMedicine(Request $request)
{
    // Validate incoming request data
    $validator = Validator::make($request->all(), [
        'patientId' => 'required|exists:patient,Pat_ID',
        'doctorId' => 'required|exists:employee,EmployeeID',
        'medicine' => 'required|array',
        'medicine.*' => 'exists:medicine,Medicine_ID',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Create a new medicine request
    $medicineRequests = [];

    foreach ($request->medicine as $medicineId) {
        $medicine = Medicine::find($medicineId);

        if (!$medicine) {
            return response()->json([
                'status' => 'fail',
                'message' => "Medicine with ID $medicineId not found",
            ], 404);
        }

        $medicineRequest = medicinerequests::create([
            'patientId' => $request->patientId,
            'doctorId' => $request->doctorId,
            'medicine' => $medicine->MedName, // Ensure 'medicine' is mapped correctly
        ]);

        $medicineRequests[] = $medicineRequest;
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Medicine request created successfully',
            'medicine_requests' => $medicineRequests,
        ],
    ], 201);
}
public function requestOxygen(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'patientId' => 'required|exists:patient,Pat_ID', // Correct table name and field
            'doctorId' => 'required|exists:employee,EmployeeID', // Correct table name and field
            'oxygenLevel' => 'required|numeric|min:0|max:100',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        // Create a new oxygen request
        $oxygenRequest = oxygen::create([
            'patientId' => $request->patientId,
            'doctorId' => $request->doctorId,
            'oxygenLevel' => $request->oxygenLevel,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Oxygen request created successfully',
                'oxygen_request' => $oxygenRequest,
            ],
        ], 201);
    }
    public function requestLab(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'doctorId' => 'required|exists:employee,EmployeeID', // Correct table and column
        'patientId' => 'required|exists:patient,Pat_ID', // Correct table and column
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Create a new lab request
    $labRequest = LabRequest::create([
        'patientId' => $request->patientId,
        'name' => $request->name,
        'doctorId' => $request->doctorId,
        'description' => $request->description,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Lab request created successfully',
            'lab_request' => $labRequest,
        ],
    ], 201);

}
}
