<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prescribe extends Model
{
    use HasFactory;
    protected $table = 'prescribe';
    protected $primaryKey = 'prescribe_ID';
    protected $fillable = [
        'prescribe_Date',
        'prescribe_Time',
        'pharm_id',
        "Pat_ID",

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

