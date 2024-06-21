<?php

namespace App\Http\Controllers\Api;

use App\Models\LabRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\oxygen;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Medicine;
use App\Models\DrReports;
use App\Models\radiology;
use App\Models\ExitRequest;
use App\Models\Adminstrator;
use Illuminate\Validation\Rule;
use App\Models\medicinerequests;
use App\Models\ConsultationRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class RadiologistController extends Controller
{
    public function radTasks(Request $request)
    {
        // Validate incoming request, if needed
        $validator = Validator::make($request->all(), [
            'patientId' => 'required|integer',
            'doctorId' => 'required|integer',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $radTasks = new radiology();
        $radTasks->radiology_name = $request->input('name');
        $radTasks->description = $request->input('description');
        $radTasks->doctor_id = $request->input('doctorId');
        $radTasks->patient_id = $request->input('patientId');
        $radTasks->save();
        return response()->json([
            'status' => 'success',
            'data' => $radTasks,
        ], 201);
    }



    /**
     * Get completed lab tasks for a radiologist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completedRadiologyTasks($id)
    {
        $Radiology = Radiology::find($id);

        if (!$Radiology) {
            return response()->json([
                'status' => 'fail',
                'message' => 'task not found',
            ], 404);
        }

        // Update task status to completed
        $Radiology->completed = true; // Assuming you have a 'completed' column in your 'nurse_tasks' table
        $Radiology->save();

        return response()->json([
            'status' => 'success',
            'message' => 'task marked as completed',
            'data' => $Radiology,
        ]);
    }

    public function getCompletedRadiologyTasks(Request $request)
    {
    // Retrieve completed radiology tasks
    $completedRadiologyTasks = Radiology::where('completed', true)->get();

    // Combine both sets of completed tasks into a single collection
    return response()->json([
        'status' => 'success',
        'message' => 'Completed tasks retrieved successfully',
        'data' => [
            'completedRadiologyTasks' => $completedRadiologyTasks,
        ],
    ]);
    }

public function getRadiologyRequests()
    {
        // Find the nurse task by ID
        $radiologyTask = radiology::all();
        return response()->json([
            'status' => 'success',
            'message' => 'radiology requests retrieved successfully',
            'data' => $radiologyTask,
        ]);
}

public function deleteTask($id)
{
    // Find the nurse task by ID
    $nurseTask = radiology::find($id);

    if (!$nurseTask) {
        return response()->json([
            'status' => 'fail',
            'message' => 'radiology task not found',
        ], 404);
    }

    // Delete the nurse task
    $nurseTask->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'radiology task deleted successfully',
    ]);
}

}
