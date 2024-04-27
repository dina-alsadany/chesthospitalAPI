<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrReports extends Model
{
    use HasFactory;
    protected $table = 'dr_reports';
    protected $primaryKey = 'DReport_ID'; // Remove the space
    protected $fillable = [
        'DReTime',
        'Result',
        'RDescription',
        'RDate',
        'RName',
        'DoctorID',
       
    ];

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
