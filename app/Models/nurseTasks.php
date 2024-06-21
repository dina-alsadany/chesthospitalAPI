<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nurseTasks extends Model
{
    use HasFactory;
    protected $table = 'nurse_tasks';
    protected $primaryKey = 'id';
    protected $fillable = [

           'name',
           'description',
           'deadline',
            'doctorId',
            'patientId',
            'completed',
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


