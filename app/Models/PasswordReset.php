<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    
    public $primaryKey = 'email';

    protected $hidden = [
		'token'
	];

	protected $fillable = [
		'email',
		'token',
		'expires_at'
	];
}
