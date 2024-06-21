<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class labs_results extends Model
{
    use HasFactory;
    protected $table = 'labs_results';

    protected $primaryKey = 'id';

    protected $fillable = [
        'LName',
        'LDescription',
        'LResult',
        'doctor_id',
        'patient_id',

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


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'Pat_ID');
    }
}
