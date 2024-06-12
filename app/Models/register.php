<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register extends Model
{
    use HasFactory;
    protected $table = 'register';
    protected $primaryKey = 'Register_id';
    protected $fillable = [
        'recep_id',
        'pat_id',
        'Regtime',
        'Regdate',
    ];
    // public function receptionist()
    // {
    //     return $this->belongsTo(receptionist::class, 'recep_id');
    // }
    // public function patient()
    // {
    //     return $this->belongsTo(Patient::class, 'pat_id');
    // }
    public function patient() {
        return $this->hasOne(Patient::class, 'UserID'); // assuming 'UserID' links users to patients
    }

    public function receptionist() {
        return $this->hasOne(Receptionist::class, 'UserID'); // assuming 'UserID' links users to receptionists
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
