<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
		'country_id'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

	public function country()
	{
		return $this->belongsTo('App\Models\Country');
	}

	public function cities()
	{
		return $this->hasMany('App\Models\City');
	}
}
