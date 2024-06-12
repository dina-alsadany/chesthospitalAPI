<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oxygen extends Model
{
    use HasFactory;
    protected $table = 'oxygens';
    protected $fillable = [

        'doctor_id',
        'patient_id',
        'num_levels',
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
