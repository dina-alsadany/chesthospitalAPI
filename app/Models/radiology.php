<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radiology extends Model
{
    use HasFactory;
    protected $table = 'radiologies';
    protected $fillable = [

        'doctor_id',
        'patient_id',
        'radiology name',
        'description'
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
