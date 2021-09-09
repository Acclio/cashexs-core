<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'gender',
        'dob',
        'phone',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'active',
        'email',
        'password',
        'last_login',
        'email_verified'
    ];

    public function user_identification()
    {
        return $this->hasOne('App\Models\UserIdentification', 'user_id', 'id');
    }

	public function country()
	{
		return $this->belongsTo('App\Models\Country');
	}

	public function state()
	{
		return $this->belongsTo('App\Models\State');
	}

	public function city()
	{
		return $this->belongsTo('App\Models\City');
	}

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
}
