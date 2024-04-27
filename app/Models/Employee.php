<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    protected $primaryKey = 'EmployeeID';
    protected $fillable = [
        'Name',
        'Address',
        'Phone',
        'Email',
        'EmployeeType',
        'shift',
        'BIRTHDAY',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // If EmployeeID is not an auto-incrementing integer, you might need to specify it as the primary key
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
