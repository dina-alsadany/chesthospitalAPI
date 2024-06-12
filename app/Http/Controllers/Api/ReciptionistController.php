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
        public function searchOrCreatePatient(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'F_Name' => 'sometimes|string|max:100',
                'L_Name' => 'sometimes|string|max:100',
                'Email' => 'sometimes|email|max:100',
                'Phone_Number' => 'sometimes|string|max:15',
            ]);

            // If validation fails, return a JSON response with errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                ], 400);
            }

            // Ensure at least one valid search parameter is provided
            if (!($request->filled(['national_id']) )) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        'warning' => ['you should serach with patient national id.']
                    ],
                ], 400);
            }

            // Build the query based on provided search parameters
            $query = Patient::query();

            if ($request->filled(['F_Name', 'L_Name'])) {
                $query->where('F_Name', 'LIKE', '%' . $request->F_Name . '%')
                      ->where('L_Name', 'LIKE', '%' . $request->L_Name . '%');
            }

            if ($request->filled('Email')) {
                $query->where('Email', 'LIKE', '%' . $request->Email . '%');
            }

            if ($request->filled('Phone_Number')) {
                $query->where('Phone_Number', 'LIKE', '%' . $request->Phone_Number . '%');
            }

            // Execute the query and get the results
            $patient = $query->get();

            // Check if any patient were found and return the appropriate response
            if ($patient->isEmpty()) {
                $searchType = '';
                if ($request->filled(['F_Name', 'L_Name'])) {
                    $searchType = 'first name and last name';
                }
                if ($request->filled('Email')) {
                    $searchType .= ($searchType ? ', ' : '') . 'email';
                }
                if ($request->filled('Phone_Number')) {
                    $searchType .= ($searchType ? ', ' : '') . 'phone number';
                }

                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        'search' => ["No patient found with the provided $searchType."]
                    ],
                ], 404);
            }

            // Return the search results
            return response()->json([
                'status' => 'success',
                'data' => $patient,
            ], 200);
        }
         /**
         * Search for a patient by ID or phone number, and create if not found.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function CreatePatient(Request $request)
        {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:100',
                Rule::unique('patient')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                }),
            ],

            'Phone_Number' => [
                'required',
                'string',
                'min:11',
                Rule::unique('patient')->where(function ($query) use ($request) {
                    return $query->where('Phone_Number', $request->Phone_Number);
                }),
            ],
            'address' => 'required|string|min:6',

            // 'Email' => [
            //     'required',
            //     'email',
            //     'max:100',
            //     Rule::unique('patient')->where(function ($query) use ($request) {
            //         return $query->where('Email', $request->Email);
            //     }),
            // ],
            // 'AccHome' => 'required|boolean',
            // 'Accwork' => 'required|boolean',
            // 'Accstreet' => 'required|boolean',
            'Medical_History' => 'required|string|min:6',
            // 'hos_ID'=> 'exists:hospital,hos_ID|nullable',
            // 'DoctorID'=> 'exists:doctor,DoctorID|nullable',
            // 'NurseID'=> 'exists:nurse,NurseID|nullable',
            // 'MR_ID'=> 'exists:medical_record,MR_ID|nullable',
            'national_id' => [
                'required',
                'string',
                'min:11',
                Rule::unique('patient')->where(function ($query) use ($request) {
                    return $query->where('national_id', $request->national_id);
                }),
            ],
            'dateOfBirth'=>'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors()->toJson(),
            ], 400);
        }

        // Create the new patient
        $patient = Patient::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Patient successfully registered',
                'patient' => $patient,
            ],
        ], 201);
    }

}
