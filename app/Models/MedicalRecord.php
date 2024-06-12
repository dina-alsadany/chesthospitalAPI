<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;
    protected $table = 'medical_record';
    protected $primaryKey = 'MR_ID'; // Remove the space
    protected $fillable = [
        'Endemic',
        'Medicine',
        'IDDM',
        'EX_Clinic',
        'Bp',
        
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // If EmployeeID is not an auto-incrementing integer, you might need to specify it as the primary key
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
