<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitRequest extends Model
{
    use HasFactory;
    protected $table = 'exitrequests';
    protected $primaryKey = 'id';

    protected $fillable = [
'patient_id',
'doctor_id'    ];



    public function patient()
    {
        return $this->belongsTo(Patient::class);
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
