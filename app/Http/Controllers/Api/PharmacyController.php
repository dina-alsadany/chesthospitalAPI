<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use App\Models\pharmacy;
use App\Models\pharmacist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'account']]);
    }
    //pharmacy
public function pharmacy(Request $request){
    $validator = Validator::make($request->all(), [
        'Mediciene_Availabilty' => 'required|boolean',
        'hos_ID' => 'exists:hospital,hos_ID|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }
    $pharmacy = pharmacy::create($validator->validated());

    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Record successfully inserted',
            'pharmacy' => $pharmacy,
        ],
    ], 201);
}
//pharmacist
public function pharmacist(Request $request){
    $validator = Validator::make($request->all(), [
        'EmployeeID' => 'exists:employee,EmployeeID|nullable',
        'license_number' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }
    $pharmacist = pharmacist::create($validator->validated());
    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Record successfully inserted',
            'pharmacist' => $pharmacist,
        ],
    ], 201);
}
    public function medicine(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'MedName' => 'required|string|max:255',
            'MDescription' => 'required|string',
            'Dosage_instructions' => 'required|string',
            'Pharmacy_ID' => 'required|exists:pharmacy,Pharmacy_ID',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors()->toJson(),
            ], 400);
        }

        // Create a new medicine instance
        $medicine = Medicine::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Medicine added successfully',
                'medicine' => $medicine,
            ],
        ]);
    }
    public function editMedicine(Request $request, $Medicine_ID)
{
     // Find the patient by ID
     $medicine = Medicine::find($Medicine_ID);

     if (!$medicine) {
         return response()->json([
             'status' => 'fail',
             'message' => 'medicine not found',
         ], 404);
     }

     // Update medicine data with the fields provided in the request
     $medicine->update($request->only([
        'MedName' ,
            'MDescription',
            'Dosage_instructions',
            'Pharmacy_ID',
     ]));

     return response()->json([
         'status' => 'success',
         'data' => [
             'message' => 'medicine data updated successfully',
             'medicine' => $medicine,
         ],
     ]);
 }
}
