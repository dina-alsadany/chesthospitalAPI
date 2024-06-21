<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use App\Models\Employee;
use App\Models\Receptionist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReciptionistController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function receptionist(Request $request){
        $validator = Validator::make($request->all(), [
            'experience_year' => 'required|int',
            'EmployeeID' => 'exists:employee,EmployeeID|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors()->toJson(),
            ], 400);
        }
        $newRecord = Receptionist::create($validator->validated());
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Record successfully inserted',
                'record' => $newRecord,
            ],
        ], 201);
    }

        /**
         * Search for a patient by ID or phone number, and create if not found.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function searchPatient(Request $request, $national_id)
        {
            // Validate the national_id parameter
            $validator = Validator::make(['id' => $national_id], [
                'id' => 'required|string|max:11', // Assuming national_id is a string with max length 11
            ]);

            // If validation fails, return a JSON response with errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                ], 400);
            }

            // Build the query based on provided search parameters
            $query = Patient::query();

            if (!empty($national_id)) {
                $query->where('national_id', 'LIKE', '%' . $national_id . '%');
            }

            // Execute the query and get the results
            $patients = $query->get();

            // Check if any patients were found and return the appropriate response
            if ($patients->isEmpty()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        'search' => ["No patient found with the provided national ID."]
                    ],
                ], 404);
            }

            // Optionally, hide certain attributes if needed
            $patients->makeHidden(['hos_ID', 'DoctorID', 'NurseID', 'MR_ID']);

            // Return the search results
            return response()->json([
                'status' => 'success',
                'data' => $patients,
            ], 200);
        }

         /**
         * Search for a patient by ID or phone number, and create if not found.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function createPatient(Request $request)
    {
        // Map incoming request fields to model attributes
        $data = [
            'name' => $request->input('name'),
            'Phone_Number' => $request->input('phone'),
            'address' => $request->input('address'),
            'Email' => $request->input('email'),
            'national_id' => $request->input('id'),
            'dateOfBirth' => $request->input('dateOfBirth'),
        ];

        $validator = Validator::make($data, [
            'name' => [
                'required',
                'max:100',
                Rule::unique('patient')->where(function ($query) use ($data) {
                    return $query->where('name', $data['name']);
                }),
            ],
            'Phone_Number' => [
                'required',
                'string',
                'min:11',
                Rule::unique('patient')->where(function ($query) use ($data) {
                    return $query->where('Phone_Number', $data['Phone_Number']);
                }),
            ],
            'address' => 'required|string|min:6',
            'Email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('patient')->where(function ($query) use ($data) {
                    return $query->where('Email', $data['Email']);
                }),
            ],
            'national_id' => [
                'required',
                'string',
                'min:11',
                Rule::unique('patient')->where(function ($query) use ($data) {
                    return $query->where('national_id', $data['national_id']);
                }),
            ],
            'dateOfBirth' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors()->toJson(),
            ], 400);
        }

        // Create the new patient
        $patient = Patient::create($validator->validated());

        // Construct the response data
        $responseData = [
            'Pat_ID' => $patient->Pat_ID, // Assuming `Pat_ID` is the primary key
            'name' => $patient->name,
            'Phone_Number' => $patient->Phone_Number,
            'Email' => $patient->Email,
            'dateOfBirth' => $patient->dateOfBirth,
            'address' => $patient->address,
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Patient successfully registered',
                'patient' => $responseData,
            ],
        ], 201);
    }
}




