<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient';
    protected $primaryKey = 'Pat_ID'; 
    protected $fillable = [
        'F_Name',
        'L_Name',
        'Phone_Number',
        'City',
        'Street',
        'Email',
        'AccHome',
        'Accwork',
        'Accstreet',
        'Medical_History',
        'hos_ID',
        'DoctorID',
        'NurseID',
        'MR_ID',
    ];

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
