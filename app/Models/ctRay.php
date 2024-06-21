<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ctRay extends Model
{
    use HasFactory;
    protected $table = 'ctRay';
    protected $primaryKey = 'ctray_ID';
    protected $fillable = [
        'ctResult',
        'ctImage',
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
