<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use App\Models\pharmacy;
use App\Models\medicines;
use App\Models\pharmacist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\medicinerequests;
use Illuminate\Support\Facades\Log;
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
 public function addMedicine(Request $request)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        // Remove 'pharmacyId' validation as it's no longer needed
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }

    // Get the authenticated user (assuming authenticated via API token)
    $user = Auth::user();

    // Create a new medicine instance with validated data and user's EmployeeID
    $medicine = Medicine::create([
        'MedName' => $request->name,
        'MDescription' => $request->description,
        'EmployeeID' => $user->EmployeeID,  // Use the authenticated user's EmployeeID
    ]);

    // Return success response with the created medicine data
    return response()->json([
        'status' => 'success',
        'data' => [
            'message' => 'Medicine added successfully',
            'medicine' => $medicine,
        ],
    ], 201);
}
public function deleteMedicine($Medicine_ID)
{
    // Find the medicine by its ID
    $medicine = Medicine::find($Medicine_ID);

    // Check if the medicine exists
    if (!$medicine) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Medicine not found',
        ], 404);
    }

    // Delete the medicine
    $medicine->delete();

    // Return success response
    return response()->json([
        'status' => 'success',
        'message' => 'Medicine deleted successfully',
    ]);
}

public function getMedicine(Request $request)
{
// Fetch all medicine requests
$medicineRequests = medicinerequests::all();

// You can modify this to filter or paginate based on your needs
return response()->json([
    'status' => 'success',
    'data' => $medicineRequests,
]);
}
public function searchMedicine(Request $request)
{
    // Validate the search query parameter
    $validator = Validator::make($request->all(), [
        'q' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'fail',
            'data' => $validator->errors()->toJson(),
        ], 400);
    }

    // Get the search query
    $query = $request->input('q');

    // Search for medicines by name or description containing the query
    $medicines = Medicine::where('MedName', 'LIKE', "%$query%")
                        ->orWhere('MDescription', 'LIKE', "%$query%")
                        ->get();

    // Check if any medicines were found
    if ($medicines->isEmpty()) {
        return response()->json([
            'status' => 'fail',
            'message' => 'No medicines found',
        ], 404);
    }

    // Return the search results
    return response()->json([
        'status' => 'success',
        'data' => $medicines,
    ], 200);
}
public function getMedicineRequest(Request $request)
 {
     // Fetch all medicine requests
     $medicineRequests = medicinerequests::all();

     // You can modify this to filter or paginate based on your needs
     return response()->json([
         'status' => 'success',
         'data' => $medicineRequests,
     ]);
 }


}
