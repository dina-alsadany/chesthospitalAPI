<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrReports extends Model
{
    use HasFactory;
    protected $table = 'dr_reports';
    protected $primaryKey = 'DReport_ID';
    protected $fillable = [
        'RDescription',
        'diagnoseillness',
        'DoctorID',
        'patient_id',

    ];
 // In DrReports model
 public function patient()
 {
     return $this->belongsTo(Patient::class, 'patient_id');
 }
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

       public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
