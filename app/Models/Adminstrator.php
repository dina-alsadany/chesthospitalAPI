<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adminstrator extends Model
{
    use HasFactory;
    protected $table = 'adminstrator';
    protected $primaryKey = 'admin_id';
    protected $fillable = [
        'admin_role',
        'admin_name',
        'Acc_id',
        'EmployeeID',
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
