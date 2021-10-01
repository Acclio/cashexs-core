<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
		'user_id',
        'beneficiary_id',
        'offer',
        'accepted'
	];

    public function bid()
	{
		return $this->belongsTo('App\Models\Bid');
	}

    public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function beneficiary()
	{
		return $this->belongsTo('App\Models\Beneficiary');
	}

    public function transactions()
	{
		return $this->hasMany('App\Models\Transaction');
	}

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'accepted' => 'boolean',
    ];
}
