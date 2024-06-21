<?php

namespace App\Http\Controllers\Api;

use App\Models\Xray;
use App\Models\ctRay;
use GuzzleHttp\Client;
use App\Models\CtResults;
use App\Models\labs_results;
use App\Models\XrayResult;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;




class MedicalImageController extends Controller
{
    public function predictXray(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $imagePath = $request->file('image')->getPathname();

        try {
            // Send a POST request to the prediction server
            $client = new Client();
            $response = $client->post('http://127.0.0.1:5000/xray/predict', [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $request->file('image')->getClientOriginalName(),
                    ],
                ],
            ]);

        $result = json_decode($response->getBody(), true);

        // Store result in the database
        // $xray = new Xray();
        // $xray->XResult = json_encode($result); // Convert result to JSON string
        // $xray->XImage = $request->file('image')->store('images', 'public'); // Store image in public/images directory
        // $xray->save();

        return response()->json([
            'status' => 'success',
            'data' => $result, // Assuming $result already contains the prediction data
        ]);
        }catch (\Exception $e) {
            // Handle any exception that may occur
            return response()->json([
                'status' => 'fail',
                'message' => 'Failed to process image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function predictCt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $imagePath = $request->file('image')->getPathname();

        try {
            // Send a POST request to the prediction server
            $client = new Client();
            $response = $client->post('http://127.0.0.1:5000/ct/predict', [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $request->file('image')->getClientOriginalName(),
                    ],
                ],
            ]);

        $result = json_decode($response->getBody(), true);
        // $ctRay = new ctRay();
        // $ctRay->ctResult = json_encode($result); // Convert result to JSON string
        // $ctRay->ctImage = $request->file('image')->store('images', 'public'); // Store image in public/images directory
        // $ctRay->save();
        return response()->json([
            'status' => 'success',
            'data' => $result, // Assuming $result already contains the prediction data
        ]);
     }catch (\Exception $e) {
        // Handle any exception that may occur
        return response()->json([
            'status' => 'fail',
            'message' => 'Failed to process image',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function labctresult(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'images.*' => 'required|image',
        'name' => 'required|string|max:25',
        'notes' => 'required|string|max:255',
        'doctorId' => [
            'required',
            'integer',
            Rule::exists('employee', 'EmployeeID')->where(function ($query) {
                $query->where('EmployeeType', 'doctor');
            }),
        ],
        'patientId' => 'required|integer|exists:patient,Pat_ID',
        'rayID' => 'required|integer|exists:raylab,Ray_ID',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 400);
    }

    try {
        $results = [];

        // Loop through each uploaded image
        foreach ($request->file('images') as $image) {
            $imagePath = $image->getPathname();

            // Send a POST request to the prediction server
            $client = new Client();
            $response = $client->post('http://127.0.0.1:5000/ct/predict', [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $image->getClientOriginalName(),
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            // Insert data into the labs_results table for each image
            $lab = new CtResults();
            $lab->CTName = $request->input('name');
            $lab->CTDescription = $request->input('notes');
            $lab->CTResult = json_encode($result); // Storing the result as JSON string
            $lab->doctor_id = $request->input('doctorId');
            $lab->patient_id = $request->input('patientId');
            $lab->Ray_ID = $request->input('rayID');
            $lab->save();

            // Collect results to return
            $results[] = [
                'filename' => $image->getClientOriginalName(),
                'result' => $result,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $results, // Array of results for each image
        ]);
    } catch (\Exception $e) {
        // Handle any exception that may occur
        return response()->json([
            'status' => 'fail',
            'message' => 'Failed to process images',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function labXRayResult(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'images.*' => 'required|image',
        'name' => 'required|string|max:25',
        'notes' => 'required|string|max:25',
        'doctorId' => [
            'required',
            'integer',
            Rule::exists('employee', 'EmployeeID')->where(function ($query) {
                $query->where('EmployeeType', 'doctor');
            }),
        ],
        'patientId' => 'required|integer|exists:patient,Pat_ID',
        'rayID' => 'required|integer|exists:raylab,Ray_ID',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 400);
    }

    try {
        $results = [];

        // Loop through each uploaded image
        foreach ($request->file('images') as $image) {
            $imagePath = $image->getPathname();

            // Send a POST request to the prediction server
            $client = new Client();
            $response = $client->post('http://127.0.0.1:5000/xray/predict', [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $image->getClientOriginalName(),
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            // Insert data into the labs_results table for each image
            $lab = new XrayResult();
            $lab->XName = $request->input('name');
            $lab->XRDescription = $request->input('notes');
            $lab->XResult = json_encode($result); // Storing the result as JSON string
            $lab->doctor_id = $request->input('doctorId');
            $lab->patient_id = $request->input('patientId');
            $lab->Ray_ID = $request->input('rayID');
            $lab->save();

            // Collect results to return
            $results[] = [
                'filename' => $image->getClientOriginalName(),
                'result' => $result,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $results, // Array of results for each image
        ]);
    } catch (\Exception $e) {
        // Handle any exception that may occur
        return response()->json([
            'status' => 'fail',
            'message' => 'Failed to process images',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
