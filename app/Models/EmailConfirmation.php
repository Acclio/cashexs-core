<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfirmation extends Model
{
    use HasFactory;
    
    protected $hidden = [
		'token'
	];

	protected $fillable = [
		'email',
		'token',
		'expires_at'
	];
}
