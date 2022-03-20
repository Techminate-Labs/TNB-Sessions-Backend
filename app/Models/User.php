<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function account(){
        return $this->hasOne(Account::class);
    }

    public function session(){
        return $this->belongsToMany(Session::class)->withTimeStamps();
    }

    public function withdraw(){
        return $this->hasMany(Withdraw::class);
    }

    // Password reset
    public function sendPasswordResetNotification($token)
    {
        $url = 'https://tnbpos.com/reset-password?token=' . $token;

        $this->notify(new ResetPasswordNotification($url));
    }
}
