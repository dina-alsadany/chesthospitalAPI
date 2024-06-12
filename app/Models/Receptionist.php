<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receptionist extends Model
{
    use HasFactory;
    protected $table = 'receptionist';
    protected $primaryKey = 'recep_id';
    protected $fillable = [
        'experience_year',
        'EmployeeID',

    ];
    public function register()
    {
        return $this->hasOne(register::class, 'recep_id');
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
