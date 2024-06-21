<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oxygen extends Model
{
    use HasFactory;
    protected $table = 'oxygenrequests';
    protected $fillable = [

        'patientId',
        'doctorId',
        'oxygenLevel',
        'name'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
