<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account';
    protected $primaryKey = 'Acc_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acc_password',
        'acc_email',
        'acc_type',
        'EmployeeID',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'acc_password',
        'remember_token',
        'role_2'
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
    public function isAdmin()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'admin';
    }
    public function isDoctor()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'doctor';
    }

    public function isReceptionist()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'receptionist';
    }
    public function isNurse()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'nurse';
    }
    public function isPharmacy()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'pharmacy';
    }

    public function isRadiologist()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'radiologist';
    }
    public function isLab()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'lab';
    }
    public function isLabAdmin()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'lab-admin';
    }
    public function isNurseAdmin()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'nurse-admin';
    }
    public function isRadiologistAdmin()
    {
        // Assuming 'acc_type' column is used to differentiate account types
        return $this->acc_type === 'radiologist-admin';
    }

}
