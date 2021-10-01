<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
		'user_id',
		'source_name',
        'source_account',
        'source_bank',
        'source_country',
        'beneficiary_name',
        'beneficiary_account',
        'beneficiary_bank',
        'beneficiary_country',
        'amount',
        'reference',
        'type'
	];

    public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function offer()
	{
		return $this->belongsTo('App\Models\Offer');
	}

}
