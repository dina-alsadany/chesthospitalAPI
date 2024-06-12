<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            'Name' => 'required|max:100',
            'Address' => 'required|string|min:6',
            'Phone' => [
                'required',
                'string',
                'min:11',
                Rule::unique('employee')->where(function ($query) use ($request) {
                    return $query->where('Phone', $request->Phone);
                }),
            ],
            'Email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('employee')->where(function ($query) use ($request) {
                    return $query->where('Email', $request->Email);
                }),
            ],
            'EmployeeType' => 'required|string|between:2,100',
            'shift' => 'required|string|between:2,100',
            'BIRTHDAY' => 'required|date_format:Y-m-d',
'password' => 'required|string|between:2,100'    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }

    // Create the new employee
    $employee = Employee::create(array_merge(
        $validator->validated(),
        ['password' => bcrypt($request->password)]
    ));

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

    // Delete employee
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
             'message' => 'employee data updated successfully',
             'employee' => $employee,
         ],
     ]);
 }
 public function AdminSearchPatient($patientId)
    {
        // Find the patient by ID
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Patient not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $patient,
        ]);
    }
}
