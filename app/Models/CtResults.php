<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtResults extends Model
{
    use HasFactory;
    protected $table = 'ctray_results';

    protected $primaryKey = 'CTRay_ID';

    protected $fillable = [
        'CTName',
        'CTDescription',
        'CTResult',
        'Ray_ID'

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
