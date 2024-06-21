<?php

namespace App\Http\Controllers\Api;

use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Raylab;
use App\Models\Account;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\pharmacy;
use App\Models\register;
use App\Models\DrReports;
use App\Models\prescribe;
use App\Models\pharmacist;
use App\Models\Adminstrator;
use App\Models\Receptionist;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\NurseSchedule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'account']]);
 }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 422);
        }

        // Retrieve the user by email
        $user = Account::where('acc_email', $request->email)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => [
                    'email' => 'The email does not exist.',
                ],
            ], 404);
        }

        // Attempt authentication
        if (!auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password])) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized',
                'data' => [
                    'password' => 'The password is incorrect.',
                ],
            ], 401);
        }

        // Retrieve the employee record
        $employee = Employee::where('Email',  $request->email)->first();

        if ($employee && $employee->EmployeeType === 'admin') {
            // Authentication successful, return token
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

            // Return success response with token and user data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60,
                    'user' => auth()->user(),
                ],
            ]);
        }
        elseif ($employee && $employee->EmployeeType === 'doctor') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'receptionist') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'nurse') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'pharmacist') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'radiologist') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'lab') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'lab-admin') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'nurse-admin') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        elseif ($employee && $employee->EmployeeType === 'radiologist-admin') {
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

        // Return success response with token and user data
        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ],
        ]);}
        else {
            // Attempt authentication for non-administrator users
            $token = auth()->attempt(['acc_email' =>  $request->email, 'acc_password' => $request->password]);
            $user -> makeHidden(['role', 'role_2']);

            // Return success response with token and user data
            return response()->json([
                'status' => 'success',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60,
                    'user' => auth()->user(),
                ],
            ]);
        }

    }


/**
 * Register a new account.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function account(Request $request)
{

    $validator = Validator::make($request->all(), [
        'acc_email' => [
            'required', 'email', 'max:100',
            Rule::exists('employee', 'Email'), // Check if the email exists in the employees table
            'unique:account',
        ],
        'acc_password' => 'required|string|min:6',
        'acc_type' => 'required|string|between:2,100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }

    // Retrieve the employee record based on the provided email
    $employee = Employee::where('Email', $request->acc_email)->first();

    // Create the account using the retrieved EmployeeID
    $user = Account::create(array_merge(
        $validator->validated(),
        ['EmployeeID' => $employee->EmployeeID, 'acc_password' => bcrypt($request->acc_password)]
    ));
    $user -> makeHidden(['role', 'role_2']);
    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'User successfully registered',
            'user' => $user,
        ],
    ], 201);

}



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
/**
 * Register a new employee.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function employee(Request $request)
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
        'password' => 'required|string|between:2,100' // Corrected password field name
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }

    // Create the new employee
    $employee = Employee::create(array_merge(
        $validator->validated(),
        ['acc_password' => bcrypt($request->acc_password)]
    ));

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Employee successfully registered',
            'employee' => $employee,
        ],
    ], 201);
}


// /**
// * Register a new patient.
// *
// * @param  \Illuminate\Http\Request  $request
// * @return \Illuminate\Http\JsonResponse
// */
// public function patient(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'F_Name' => [
//             'required',
//             'max:100',
//             Rule::unique('patient')->where(function ($query) use ($request) {
//                 return $query->where('F_Name', $request->F_Name);
//             }),
//         ],
//         'L_Name' => [
//             'required',
//             'max:100',
//             Rule::unique('patient')->where(function ($query) use ($request) {
//                 return $query->where('L_Name', $request->L_Name);
//             }),
//         ],
//         'Phone_Number' => [
//             'required',
//             'string',
//             'min:11',
//             Rule::unique('patient')->where(function ($query) use ($request) {
//                 return $query->where('Phone_Number', $request->Phone_Number);
//             }),
//         ],
//         'City' => 'required|string|min:6',
//         'Street' => 'required|string|min:6',
//         'Email' => [
//             'required',
//             'email',
//             'max:100',
//             Rule::unique('patient')->where(function ($query) use ($request) {
//                 return $query->where('Email', $request->Email);
//             }),
//         ],
//         'AccHome' => 'required|boolean',
//         'Accwork' => 'required|boolean',
//         'Accstreet' => 'required|boolean',
//         'Medical_History' => 'required|string|min:6',
//         'hos_ID'=> 'exists:hospital,hos_ID|nullable',
//         'DoctorID'=> 'exists:doctor,DoctorID|nullable',
//         'NurseID'=> 'exists:nurse,NurseID|nullable',
//         'MR_ID'=> 'exists:medical_record,MR_ID|nullable',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }

//     // Create the new patient
//     $patient = Patient::create($validator->validated());

//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Patient successfully registered',
//             'patient' => $patient,
//         ],
//     ], 201);
// }


// /**
//  * Register a new medical_record.
//  *
//  * @param  \Illuminate\Http\Request  $request
//  * @return \Illuminate\Http\JsonResponse
//  */
// public function medical_record(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'Endemic'=> 'required|boolean',
//         'Medicine'=> 'required|max:100',
//         'IDDM'=> 'required|boolean',
//         'EX_Clinic'=> 'required|boolean',
//         'Bp'=> 'required|boolean',
//         'admin_id' => 'nullable|exists:adminstrator,admin_id',
//         'admin_role'=> 'required|max:100',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }

//     // Create the new medical record
//     $medicalRecord = MedicalRecord::create($validator->validated());

//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Medical record successfully registered',
//             'medical_record' => $medicalRecord,
//         ],
//     ], 201);
// }


//  // Create the new Raylab
//  public function raylab(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'lab_no' => 'string|required',
//         'lab_Equipment' => 'string|required',
//         'hos_ID' => 'exists:hospital,hos_ID|nullable',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }
//     $raylab = Raylab::create($validator->validated());
//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Raylab record successfully inserted',
//             'raylab' => $raylab,
//         ],
//     ], 201);
// }
// //register
// public function register(Request $request){

//     $validator = Validator::make($request->all(), [
//         'recep_id' => 'exists:receptionist,recep_id|nullable',
//         'pat_id' => 'exists:patient,Pat_ID|nullable',
//         'Regtime' => 'required|date_format:H:i:s',
//         'Regdate' => 'required|date_format:Y-m-d',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);

// }
// $register = register::create($validator->validated());
//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'register record successfully inserted',
//             'register' => $register,
//         ],
//     ], 201);
// }

// //prescribe
// public function prescribe(Request $request){
//     $validator = Validator::make($request->all(), [
//         'prescribe_Date' => 'required|date_format:Y-m-d',
//         'prescribe_Time' => 'required|date_format:H:i:s',
//         'pharm_id' => 'exists:pharmacist,pharm_id|nullable',
//         'Pat_ID' => 'exists:patient,Pat_ID|nullable',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }
//     $newRecord = prescribe::create($validator->validated());
//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Record successfully inserted',
//             'record' => $newRecord,
//         ],
//     ], 201);
// }
// public function gettoken(){
//     return csrf_token();
// }
// public function doctor(Request $request){
//     $validator = Validator::make($request->all(), [
//         'licence_number' => 'required|int',
//         'years_experiance' => 'required|int',
//         'Specialization' => 'required|string',
//         'EmployeeID' => 'exists:employee,EmployeeID|nullable',
//     ]);


//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }
//     $newRecord = Doctor::create($validator->validated());
//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Record successfully inserted',
//             'record' => $newRecord,
//         ],
//     ], 201);
// }
// public function adminstrator(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'admin_role' => 'required|string',
//         'admin_name' => 'required|string',
//         'Acc_id' => 'required|exists:account,Acc_id|nullable',
//         'EmployeeID' => 'required|exists:employee,EmployeeID|nullable',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'fail',
//             'data' => $validator->errors()->toJson(),
//         ], 400);
//     }

//     $newRecord = Adminstrator::create($validator->validated());

//     return response()->json([
//         'status' => 'success',
//         'data' => [
//             'message' => 'Record successfully inserted',
//             'record' => $newRecord,
//         ],
//     ], 201);
// }


public function nurse(Request $request){
    $validator = Validator::make($request->all(), [
        'Department' => 'required|string',
        'nursing_spectialty' => 'required|string',
        'years_experiance' => 'required|int',
        'EmployeeID' => 'required|exists:employee,EmployeeID|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }
    $newRecord = Nurse::create($validator->validated());
    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Record successfully inserted',
            'record' => $newRecord,
        ],
    ], 201);
}
// }
public function nurseschedule(Request $request){
    $validator = Validator::make($request->all(), [
        'NSTime' => 'required|date_format:H:i:s',
        'StateOfHealth' => 'required|string',
        'RDate' => 'required|date_format:Y-m-d',
        'pat_id'  => 'exists:patient,Pat_ID|nullable',
        'DoctorID' => 'exists:doctor,DoctorID|nullable',
        'NurseID' => 'exists:nurse,NurseID|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }
    $newRecord = NurseSchedule::create($validator->validated());
    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Record successfully inserted',
            'record' => $newRecord,
        ],
    ], 201);
}

}
