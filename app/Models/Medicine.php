<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $table = 'medicine';
    protected $primaryKey = 'Medicine_ID';
    protected $fillable = [
        'MedName',
        'MDescription',
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
    public function requests()
{
    return $this->hasMany(medicinerequests::class, 'medicine', 'MedName');
}

}

