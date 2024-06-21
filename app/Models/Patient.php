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
        'name',
        'Phone_Number',
        'address',
        'national_id',
        'dateOfBirth',
        'Email' // Added Email to fillable
    ];
    public function register()
    {
        return $this->hasOne(Register::class, 'pat_id');
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
    public function medicineRequests()
{
    return $this->hasMany(medicinerequests::class, 'patientId', 'Pat_ID');
}
}
