<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Requests\UserIDRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

class UserController extends ServiceController
{
    public function show($id)
    {
        try {
            $user = User::find($id);

            if(!$user)
            {
                $message = 'User not found';
                return $this->not_found(null, $message);
            }

            return $this->success($user);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }
}
