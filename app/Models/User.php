<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name',
        'Address',
        'Phone',
        'Email',
        'EmployeeType',
        'shift',
        'BIRTHDAY',
        'password',
        'is_admin',
        'is_doctor',
        'is_receptionist',
        'is_nurse',
        'is_pharmacy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'acc_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Define a relationship with the Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }
    public function getAuthPassword()
{
    return $this->acc_password;
}
public function pharmacy()
{
    return $this->hasOne(Pharmacy::class, 'user_id'); // Adjust 'user_id' to match your database schema
}
}
