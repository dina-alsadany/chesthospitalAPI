<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicinerequests extends Model
{
    use HasFactory;
     protected $table = 'medicinerequests';

    protected $primaryKey = 'id';
    protected $fillable = [
        'patientId',
        'doctorId',
        'medicine', // ensure this corresponds to the 'name' in the Medicine model
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
