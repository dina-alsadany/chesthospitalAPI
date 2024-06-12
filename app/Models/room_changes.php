<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room_changes extends Model
{
    use HasFactory;
    protected $table = 'room_changes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ER_Rom',
        'General_Rom',
        'changed_by',
        'patient_id'
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
