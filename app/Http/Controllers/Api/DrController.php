<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Nurse;
use App\Models\Doctor;
use App\Models\Raylab;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\DrReports;
use App\Models\medicines;
use App\Models\LabRequest;
use App\Models\Adminstrator;
use App\Models\Receptionist;
use App\Models\room_changes;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\NurseSchedule;
use App\Models\ConsultationRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\AuthController;

class DrController extends Controller
{

/**
    * Create a new AuthController instance.
    *
    * @return void
    */
    /**
     * Search for a patient by first name, last name, email, or phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function searchPatient($patientId)
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
    /**
 * Register a new dr_reports.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */

        public function add_reports(Request $request, $patient_id)
        {
            // Check if user is authenticated
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Debugging: Log authenticated user information
            Log::info('Authenticated user:', ['user' => $user]);

            // Retrieve DoctorID using EmployeeID
            $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
            if (!$doctor) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Doctor not found for authenticated employee',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'diagnoseillness' => 'required|string|max:255',
                'prescription' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'data' => $validator->errors(),
                ], 400);
            }

            // Ensure the patient exists
            $patient = Patient::find($patient_id);
            if (!$patient) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Patient not found',
                ], 404);
            }

            // Create the report
            $drReport = DrReports::create([
                'diagnoseillness' => $request->diagnoseillness,
                'RDescription' => $request->prescription,
                'DoctorID' => $doctor->DoctorID,
                'patient_id' => $patient_id,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'Report successfully created',
                    'report' => $drReport,
                ],
            ], 201);
        }


/**
     * Order medicine from NurseSchedule.
     */
    public function orderMedicine(Request $request, $patientId)
{
    // Check if the patient exists
    $patient = Patient::find($patientId);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient with ID ' . $patientId . ' not found.',
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'medicine' => 'required|string|max:255',
        'schedule_time' => 'required|date_format:H:i:s',
        'schedule_date' => 'required|date_format:Y-m-d',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    $nurseSchedule = NurseSchedule::create([
        'NSTime' => $request->schedule_time,
        'StateOfHealth' => 'Medicine order: ' . $request->medicine,
        'RDate' => $request->schedule_date,
        'pat_id' => $patientId,
        'DoctorID' => auth()->user()->id,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => $nurseSchedule,
    ], 201);
}
/**
     * Exit order for patient.
     */
    public function exitOrder(Request $request, $patientId)
    {
        $validator = Validator::make($request->all(), [
            'exit_date' => 'required|date_format:Y-m-d',
            'exit_time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        $adminstrator = Adminstrator::create([
            'admin_role' => 'exit order',
            'admin_name' => auth()->user()->name,
            'Acc_id' => auth()->user()->id,
            'EmployeeID' => auth()->user()->EmployeeID,
            'exit_date' => $request->exit_date,
            'exit_time' => $request->exit_time,
            'patient_id' => $patientId,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $adminstrator,
        ], 201);
    }

    /**
     * General order from nurse.
     */
    public function generalOrder(Request $request, $nurseId)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|string|max:255',
            'schedule_time' => 'required|date_format:H:i:s',
            'schedule_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        $nurseSchedule = NurseSchedule::create([
            'NSTime' => $request->schedule_time,
            'StateOfHealth' => 'General order: ' . $request->order,
            'RDate' => $request->schedule_date,
            'NurseID' => $nurseId,
            'DoctorID' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $nurseSchedule,
        ], 201);
    }

    /**
     * Change room of patient.
     */
    public function changeRoom(Request $request, $patientId)
    {
        $validator = Validator::make($request->all(), [
        'ER_Rom' => 'required|boolean',
        'General_Rom' => 'required|boolean',
        'changed_by' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }


        $roomChange = room_changes::create([
            'patient_id' => $patientId,
            'ER_Rom' => $request->ER_Rom,
            'General_Rom' => $request->General_Rom,
            'changed_by' =>  auth()->user()->DoctorID,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $roomChange,
        ], 201);
    }

    /**
     * Send X-ray or CT orders to NurseSchedule.
     */
    public function sendRayOrder(Request $request, $patientId)
    {
        $validator = Validator::make($request->all(), [
            'order_type' => 'required|in:x-ray,ct',
            'description' => 'required|string|max:255',
            'schedule_time' => 'required|date_format:H:i:s',
            'schedule_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors(),
            ], 400);
        }

        $rayOrder = Raylab::create([
            'lab_no' => $patientId,
            'lab_Equipment' => $request->order_type . ': ' . $request->description,
            'hos_ID' => auth()->user()->id,
        ]);

        $nurseSchedule = NurseSchedule::create([
            'NSTime' => $request->schedule_time,
            'StateOfHealth' => ucfirst($request->order_type) . ' order: ' . $request->description,
            'RDate' => $request->schedule_date,
            'pat_id' => $patientId,
            'DoctorID' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => ['ray_order' => $rayOrder, 'nurse_schedule' => $nurseSchedule],
        ], 201);
    }
    public function getPatientData($patientId)
    {
        // Fetch the patient
        $patient = Patient::find($patientId);

        if (!$patient) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Patient not found',
            ], 404);
        }

        // Fetch related medical records
        $medicalRecords = MedicalRecord::where('patient_id', $patientId)->get();

        // Fetch related doctor's reports
        $drReports = DrReports::where('patient_id', $patientId)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'patient' => $patient,
                'medical_records' => $medicalRecords,
                'dr_reports' => $drReports,
            ],
        ], 200);
    }
    public function add_lab_request(Request $request, $patient_id)
{
    // Check if user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'status' => 'fail',
            'message' => 'User not authenticated',
        ], 401);
    }

    // Retrieve DoctorID using EmployeeID
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if (!$doctor) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Doctor not found for authenticated employee',
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'lab_name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Ensure the patient exists
    $patient = Patient::find($patient_id);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Create the lab request
    $labRequest = LabRequest::create([
        'doctor_id' => $doctor->DoctorID,
        'patient_id' => $patient_id,
        'lab_name' => $request->lab_name,
        'description' => $request->description,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Lab request successfully created',
            'lab_request' => $labRequest,
        ],
    ], 201);
}
public function add_consultation_request(Request $request, $patient_id)
{
    // Check if user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'status' => 'fail',
            'message' => 'User not authenticated',
        ], 401);
    }

    // Retrieve DoctorID using EmployeeID
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if (!$doctor) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Doctor not found for authenticated employee',
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'doctor_name' => 'required|string|max:255',
        'doctor_specialization' => 'required|string|max:255',
        'doctor_hospital' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Ensure the patient exists
    $patient = Patient::find($patient_id);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Create the consultation request
    $consultationRequest = ConsultationRequest::create([
        'doctor_id' => $doctor->DoctorID,
        'patient_id' => $patient_id,
        'doctor_name' => $request->doctor_name,
        'doctor_specialization' => $request->doctor_specialization,
        'doctor_hospital' => $request->doctor_hospital,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Consultation request successfully created',
            'consultation_request' => $consultationRequest,
        ],
    ], 201);
}
public function requestMedicine(Request $request, $patient_id)
{
    // Check if user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'status' => 'fail',
            'message' => 'User not authenticated',
        ], 401);
    }

    // Retrieve DoctorID using EmployeeID
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if (!$doctor) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Doctor not found for authenticated employee',
        ], 404);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'num_doses' => 'required|in:1,2,3,4,5', // Ensure the number of doses is one of 1, 2, or 3
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Ensure the patient exists
    $patient = Patient::find($patient_id);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Retrieve the authenticated doctor's ID
    $doctorId = $doctor->DoctorID;

    // Create the medicine request
    $medicine = Medicines::create([
        'doctor_id' => $doctorId,
        'patient_id' => $patient_id,
        'num_doses' => $request->num_doses,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Medicine request successfully created',
            'medicine' => $medicine,
        ],
    ], 201);
}
public function requestOxygen(Request $request, $patient_id)
{
    // Check if user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'status' => 'fail',
            'message' => 'User not authenticated',
        ], 401);
    }

    // Retrieve DoctorID using EmployeeID
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if (!$doctor) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Doctor not found for authenticated employee',
        ], 404);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'num_levels' => 'required|in:1,2,3,4', // Ensure the number of doses is one of 1, 2, or 3
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Ensure the patient exists
    $patient = Patient::find($patient_id);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Retrieve the authenticated doctor's ID
    $doctorId = $doctor->DoctorID;

    // Create the medicine request
    $medicine = Medicines::create([
        'doctor_id' => $doctorId,
        'patient_id' => $patient_id,
        'num_levels' => $request->num_levels,
    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Medicine request successfully created',
            'medicine' => $medicine,
        ],
    ], 201);
}
public function requestRadiology(Request $request, $patient_id)
{
    // Check if user is authenticated
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'status' => 'fail',
            'message' => 'User not authenticated',
        ], 401);
    }

    // Retrieve DoctorID using EmployeeID
    $doctor = Doctor::where('EmployeeID', $user->EmployeeID)->first();
    if (!$doctor) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Doctor not found for authenticated employee',
        ], 404);
    }

    // Validate the request data
    $validator = Validator::make($request->all(), [
        'radiology name' => 'required|string|max:255',
        'description' => 'required|string|max:255'// Ensure the number of doses is one of 1, 2, or 3
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors(),
        ], 400);
    }

    // Ensure the patient exists
    $patient = Patient::find($patient_id);
    if (!$patient) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Patient not found',
        ], 404);
    }

    // Retrieve the authenticated doctor's ID
    $doctorId = $doctor->DoctorID;

    // Create the medicine request
    $medicine = Medicines::create([
        'doctor_id' => $doctorId,
        'patient_id' => $patient_id,
        'radiology name' => $request->radiology_name,
        'description' => $request->description,

    ]);

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Medicine request successfully created',
            'medicine' => $medicine,
        ],
    ], 201);
}
}
