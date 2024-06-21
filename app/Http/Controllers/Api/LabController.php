<?php

namespace App\Http\Controllers\Api;

use App\Models\LabRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\labs_results;

class LabController extends Controller
{
    public function completedLabTasks($id)
    {
        $labRequest = LabRequest::find($id);

        if (!$labRequest) {
            return response()->json([
                'status' => 'fail',
                'message' => 'task not found',
            ], 404);
        }

        // Update task status to completed
        $labRequest->completed = true; // Assuming you have a 'completed' column in your 'nurse_tasks' table
        $labRequest->save();

        return response()->json([
            'status' => 'success',
            'message' => 'task marked as completed',
            'data' => $labRequest,
        ]);
    }
    public function getCompletedLabTasks(Request $request)
    {
          // Retrieve completed lab tasks
    $completedLabTasks = LabRequest::where('completed', true)->get();

    // Combine both sets of completed tasks into a single collection
    return response()->json([
        'status' => 'success',
        'message' => 'Completed lab tasks retrieved successfully',
        'data' => [
            'completedLabTasks' => $completedLabTasks,
        ],
    ]);
    }
    public function getLabRequests()
    {
        $labRequests = LabRequest::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Lab requests retrieved successfully',
            'data' => $labRequests,
        ]);
    }
    public function deleteTask($id)
    {
        // Find the nurse task by ID
        $nurseTask = LabRequest::find($id);

        if (!$nurseTask) {
            return response()->json([
                'status' => 'fail',
                'message' => 'lab task not found',
            ], 404);
        }

        // Delete the nurse task
        $nurseTask->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'lab task deleted successfully',
        ]);
    }
    public function labTasks(Request $request)
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
        $LabRequest = new LabRequest();
        $LabRequest->name = $request->input('name');
        $LabRequest->description = $request->input('description');
        $LabRequest->doctorId = $request->input('doctorId');
        $LabRequest->patientId = $request->input('patientId');
        $LabRequest->save();
        return response()->json([
            'status' => 'success',
            'data' => $LabRequest,
        ], 201);
    }

    public function labResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'result' => 'required|string',
            'doctorId' => [
                'required',
                'integer',
                Rule::exists('employee', 'EmployeeID')->where(function ($query) {
                    $query->where('EmployeeType', 'doctor');
                }),
            ],
            'patientId' => 'required|integer|exists:patient,Pat_ID',
            //'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $labResult = new labs_results();
        $labResult->LName = $request->input('name');
        $labResult->LDescription = $request->input('description');
        $labResult->LResult = $request->input('result');
        $labResult->doctor_id = $request->input('doctorId');
        $labResult->patient_id = $request->input('patientId');
        $labResult->save();

        // $imagePaths = [];
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('lab_results', 'public');
        //         $imagePaths[] = $path;
        //     }
        // }

        // Store image paths in a separate field or related table if needed
        // $labResult->image_paths = json_encode($imagePaths);
        // $labResult->save();

        return response()->json([
            'status' => 'success',
            'data' => $labResult,
         //   'images' => $imagePaths,
        ], 201);
    }


}
