<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
		'name',
		'code',
        'currency',
        'currency_symbol',
        'currency_code',
        'active'
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'active',
        'created_at',
        'updated_at'
    ];

	public function banks()
	{
		return $this->hasMany('App\Models\Bank');
	}

    public function states()
	{
		return $this->hasMany('App\Models\State');
	}

    public function users()
	{
		return $this->hasMany('App\Models\User');
	}
}
