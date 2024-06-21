<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table = 'doctor';
    protected $primaryKey = 'DoctorID';
    protected $fillable = [
        'licence_number',
        'years_experiance',
        'Specialization',
        'EmployeeID',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
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
    public function consultations()
    {
        return $this->hasMany(ConsultationRequest::class, 'doctor_id', 'EmployeeID');
    }

}

