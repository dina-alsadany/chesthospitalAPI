<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XrayResult extends Model
{
    use HasFactory;



    protected $table = 'xray_results';

    protected $primaryKey = 'XRay_ID';

    protected $fillable = [
        'XName',
        'XRDescription',
        'XResult',

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
