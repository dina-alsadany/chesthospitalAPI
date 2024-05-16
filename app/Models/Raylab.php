<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raylab extends Model
{
    use HasFactory;
    protected $table = 'Raylab';
    protected $primaryKey = 'Ray_ID';
    protected $fillable = [
        'lab_no',
        'lab_Equipment',
        'hos_ID',


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


