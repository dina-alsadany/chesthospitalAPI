<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseSchedule extends Model
{
    use HasFactory;
    protected $table = 'nurseschedule';
    protected $primaryKey = 'NS_ID';
    protected $fillable = [
        'NSTime',
        'StateOfHealth',
        'RDate',
        'pat_id',
        'DoctorID',
        'NurseID'
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
