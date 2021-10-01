<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
		'user_id',
		'selling_currency_id',
        'buying_currency_id',
        'beneficiary_id',
        'amount',
        'rate',
        'status'
	];

    public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function selling()
	{
		return $this->belongsTo('App\Models\Country', 'selling_currency_id', 'id');
	}

    public function buying()
	{
		return $this->belongsTo('App\Models\Country', 'buying_currency_id', 'id');
	}

    public function beneficiary()
	{
		return $this->belongsTo('App\Models\Beneficiary');
	}

    public function offers()
	{
		return $this->hasMany('App\Models\Offer');
	}
}
