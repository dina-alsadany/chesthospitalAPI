<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends  Authenticatable implements JWTSubject
{

    use HasFactory;
    use SoftDeletes;

    protected $table = 'employee';
    protected $primaryKey = 'EmployeeID';
    protected $fillable = [
        'Name',
        'Address',
        'Phone',
        'Email',
        'EmployeeType',
        'shift',
        'BIRTHDAY',
        'password',
        'is_admin',
        'is_doctor',
        'is_receptionist',
        'is_nurse',
        'is_pharmacy',
        'is_radiologist',
        'is_lab',
        'is_lab_admin',
        'is_nurse_admin',
        'is_radiologist_admin'



    ];
     public function doctor()
    {
        return $this->hasOne(Doctor::class, 'EmployeeID');
    }
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $hidden = [
        'password',
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
    public function isAdmin()
    {
        return $this->is_admin;
    }
    public function isDoctor()
    {
        return $this->is_doctor;
    }
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    public function isReceptionist()
    {
        return $this->is_receptionist;
    }
    public function isNurse()
    {
        return $this->is_nurse;
    }
    public function isPharmacy()
    {
        return $this->is_pharmacy;
    }

    public function isRadiologist()
    {
        return $this->is_radiologist;
    }
    public function medicineRequests()
{
    return $this->hasMany(medicinerequests::class, 'doctorId', 'EmployeeID');
}
public function requestLab()
{
    return $this->hasMany(LabRequest::class, 'doctorId', 'EmployeeID');
}
public function addMedicine()
{
    return $this->hasMany(Medicine::class, 'Pharmacy_ID', 'EmployeeID');
}

}
