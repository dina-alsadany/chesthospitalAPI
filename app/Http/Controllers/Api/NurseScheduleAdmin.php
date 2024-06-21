<?php

namespace App\Http\Controllers\Api;

use App\Models\oxygen;
use App\Models\Patient;
use App\Models\radiology;
use App\Models\LabRequest;
use App\Models\nurseTasks;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\medicinerequests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NurseScheduleAdmin extends Controller
{

    /**
     * Find a patient by their ID.
     *
     * @param  int  $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function NurseSearchPatient($patientId)
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
    public function postNurseTask(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'doctorId' => 'required|integer',
            'patientId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Create the nurse task
        $nurseTask = new nurseTasks();
        $nurseTask->name = $request->input('name');
        $nurseTask->description = $request->input('description');
        $nurseTask->deadline = $request->input('deadline');
        $nurseTask->doctorId = $request->input('doctorId');
        $nurseTask->patientId = $request->input('patientId');
        $nurseTask->save();

        return response()->json([
            'status' => 'success',
            'data' => $nurseTask,
        ], 201);
    }
    public function deleteTask($id)
    {
        // Find the nurse task by ID
        $nurseTask = NurseTasks::find($id);

        if (!$nurseTask) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Nurse task not found',
            ], 404);
        }

        // Delete the nurse task
        $nurseTask->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Nurse task deleted successfully',
        ]);
    }
    public function getOxygenRequests()
    {
        // Retrieve all oxygen requests with doctor and patient relationships
        $oxygenRequests = oxygen::with('doctor', 'patient')->get();

        return response()->json([
            'status' => 'success',
            'data' => $oxygenRequests,
        ]);
    }
    public function completedTask($id)
    {
        // Find the nurse task by ID
        $nurseTask = nurseTasks::find($id);

        if (!$nurseTask) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Nurse task not found',
            ], 404);
        }

        // Update task status to completed
        $nurseTask->completed = true; // Assuming you have a 'completed' column in your 'nurse_tasks' table
        $nurseTask->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Nurse task marked as completed',
            'data' => $nurseTask,
        ]);
    }
    public function getCompletedTasks(Request $request)
    {
        // Example: Retrieve completed lab tasks
        $completedTasks = nurseTasks::where('completed', true)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'nurse tasks retrieved successfully',
            'data' => $completedTasks,
        ]);
    }
    public function getRequests()
{
       // Fetch tasks from nurseTasks
       $nurseTasks = nurseTasks::all();

       // Fetch tasks from medicinerequests
       $medicineRequests = medicinerequests::all();
       $oxygenRequests = oxygen::all();
       $radiologyRequests = radiology::all();

       $LabRequest = LabRequest::all();



       return response()->json([
           'status' => 'success',
           'message' => 'Nurse requests retrieved successfully',
           'data' => [
               'nurseTasks' => $nurseTasks,
               'medicineRequests' => $medicineRequests,
                'oxygenRequests' => $oxygenRequests,
               'radiologyRequests' => $radiologyRequests,
               'LabRequest' => $LabRequest,


           ],
       ]);
}
public function getNurseAdminRequests()
{
       // Fetch tasks from nurseTasks
       $nurseTasks = nurseTasks::all();
       return response()->json([
           'status' => 'success',
           'message' => 'Nurse requests retrieved successfully',
           'data' => [
               'nurseTasks' => $nurseTasks,
           ],
       ]);
}
}

