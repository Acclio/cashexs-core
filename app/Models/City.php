<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
		'state_id'
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

	public function state()
	{
		return $this->belongsTo('App\Models\State');
	}

	public function users()
	{
		return $this->hasMany('App\Models\User');
	}
}
