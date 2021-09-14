<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
		'bank_id',
        'account_no',
        'user_id',
        'country_id'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'country_id',
        'user_id',
		'bank_id',
    ];

    public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function bank()
	{
		return $this->belongsTo('App\Models\Bank');
	}

    public function country()
	{
		return $this->belongsTo('App\Models\Country');
	}
}
