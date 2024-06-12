<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
}
