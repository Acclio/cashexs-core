<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
		'code',
        'country_id',
        'active'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'country_id',
        'active',
        'created_at',
        'updated_at'
    ];

    public function country()
	{
		return $this->belongsTo('App\Models\Country');
	}
}
