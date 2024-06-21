<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pharmacy extends Model
{
    use HasFactory;
    protected $table = 'pharmacy';
    protected $primaryKey = 'Pharmacy_ID';
    protected $fillable = [
        'Mediciene_Availabilty',
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
    public function addMedicine()
{
    return $this->hasMany(Medicine::class, 'Pharmacy_ID', 'EmployeeID');
}

}
