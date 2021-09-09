<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Sourcery\Utilities;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordEmail;
use App\Mail\ForgotPasswordEmail;
use App\Models\EmailConfirmation;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ServiceController;
use App\Http\Requests\ConfirmSignupRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ConfirmationEmail As Confirmation;

class AccountController extends ServiceController
{
    public function signin(SigninRequest $request)
    {
        try {
            $data = $request->validated();
            $credentials = request(['email', 'password']);

            // Check credentials
            if (!Auth::attempt($credentials))
            {
                $message = 'Invalid username or password. Please check your credentials and try again';
                return $this->unauthorised(null, $message);
            }

            $user = $request->user();

            // Check for email confirmation
            if(!$user->email_verified)
            {
                $message = 'Account is unconfirmed';
                $status = 'Unconfirmed';
                return $this->forbidden(null, $message, $status);
            }

            // if the account is not active
            if(!$user->active) {
                $message = 'Account is suspended or deactivated';
                $status = 'Deactivated';
                return $this->forbidden(null, $message, $status);
            }

            // Set authentication token
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $token->save();

            $user->last_login = Carbon::now();
            $user->save();

            $response['user'] = $user;
            $response['token'] = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer Token',
                'expires' => Carbon::parse($token->expires_at)->toDateTimeString(),
            ];
            return $this->success($response);

        } catch (\Throwable $ex) {
            return $this->server_error($ex);
        }
    }

    public function signup(SignupRequest $request)
    {
        try
        {
            // Validate request
            $data = $request->validated();

            $data['active'] = true;
            $data['email_verified'] = false;
            $data['password'] = bcrypt($data['password']);

            // Persist user
            $user = User::create($data);

            // Generate token
            $confirmation_token = mt_rand(10000, 99999);

            // Persist email generation token
            EmailConfirmation::updateOrCreate(
                ['email' => $data["email"]],
                [
                    'token' => bcrypt($confirmation_token),
                    'expires_at' => Carbon::now()->addHours(12)
                ]
            );

            $callback_url = '#';

            // Get callback url
            if ($request->callback_url != null) {
                $callback_url = $request->callback_url.'?token='.$confirmation_token.'&username='.$user->username;
            }

            // Send confirmation mail
            try {
                Mail::to($data["email"])->send(new Confirmation($user, $callback_url, $confirmation_token));
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }

            $response['user'] = $user;
            return $this->created($response);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    public function confirmSignup(ConfirmSignupRequest $request)
    {
        try
        {
            $data = $request->validated();

            // Get confirmation details
            $confirmation = EmailConfirmation::where('email', $data['email'])->first();

            // Validate confirmation token
            if(!$confirmation || !Hash::check($data["token"], $confirmation->token))
            {
                $message = 'Invalid confirmation token';
                $status = 'InvalidToken';
                return $this->bad_request(null, $message, $status);
            }

            // Validate token lifespan
            if(Carbon::now()->gt($confirmation->expires_at))
            {
                $message = 'Expired confirmation token';
                $status = 'ExpiredToken';
                return $this->bad_request(null, $message, $status);
            }

            // Get user details
            $user = User::where('email', $confirmation->email)->first();
            if(!$user)
            {
                $message = 'User not found';
                return $this->not_found(null, $message);
            }

            // Verify user
            $user->email_verified = true;
            $user->email_verified_at = Carbon::now();
            $user->save();

            $confirmation->delete();

            // Generate access token
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            $callback_url = '#';

            // Get callback url
            if ($request->callback_url != null) {
                $callback_url = $request->callback_url;
            }

            // Send welcome mail
            try {
                Mail::to($user->email)->send(new WelcomeEmail($user, $callback_url));
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }

            $response['user'] = $user;
            $response['token'] = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer Token',
                'expires' => Carbon::parse($token->expires_at)->toDateTimeString(),
            ];
            return $this->success($response);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    public function forgotPassword(EmailRequest $request)
    {
        $data = $request->validated();
        try
        {
            // Get user details
            $user = User::where('email', $data['email'])->first();
            if(!$user)
            {
                $message = 'User not found';
                return $this->not_found(null, $message);
            }

            // Generate user token
            $token = mt_rand(10000, 99999);

            // Set password reset token
            PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'token' => bcrypt($token),
                    'expires_at' => Carbon::now()->addHours(12)
                ]
            );

            $callback_url = '#';

            // Get callback url
            if ($request->callback_url != null) {
                $callback_url = $request->callback_url.'?token='.$token.'&username='.$user->username;
            }

            // Mail user token
            try {
                Mail::to($user->email)->send(new ForgotPasswordEmail($user, $token, $callback_url));
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }

            $response['user'] = $user;
            return $this->success($response);
        }
        catch(\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try
        {
            $data = $request->validated();

            // Get user details
            $resetRequest = PasswordReset::where('email', $data['email'])->first();
            if(!$resetRequest)
            {
                $message = 'No password reset request found for this user';
                $status = 'NoResetRequestFound';
                return $this->not_found(null, $message, $status);
            }

            // Validate token
            if(!Hash::check($data["token"], $resetRequest->token))
            {
                $message = 'Invalid password request token';
                $status = 'InvalidToken';
                return $this->forbidden(null, $message, $status);
            }

            // Validate token lifespan
            if(Carbon::now()->gt($resetRequest->expires_at))
            {
                $message = 'Password reset token request has expired.';
                $status = 'ExpiredToken';
                return $this->forbidden(null, $message, $status);
            }

            // Update password
            $user = User::where('email', $data['email'])->first();
            $user->fill(['password' => bcrypt($data['password'])])->save();

            $resetRequest->delete();

            // send password request notification
            Mail::to($user->email)->send(new ResetPasswordEmail($user->firstname));

            $response['user'] = $user;
            return $this->success($response);
        }
        catch(\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }

    public function resendConfirmationEmail(EmailRequest $request)
    {
        $data = $request->validated();
        try
        {
            // Get user details
            $user = User::where('email', $data['email'])->first();
            if(!$user)
            {
                $message = 'User not found';
                return $this->not_found(null, $message);
            }

            if($user->email_verified == true)
            {
                $message = 'Email already confirmed';
                $status = 'AlreadyConfirmed';
                return $this->bad_request(null, $message, $status);
            }

            // Generate token
            $confirmation_token = mt_rand(10000, 99999);

            // Persist email generation token
            EmailConfirmation::updateOrCreate(
                ['email' => $data["email"]],
                [
                    'token' => bcrypt($confirmation_token),
                    'expires_at' => Carbon::now()->addHours(12)
                ]
            );

            $callback_url = '#';

            // Get callback url
            if ($request->callback_url != null) {
                $callback_url = $request->callback_url.'?token='.$confirmation_token.'&username='.$user->username;
            }

            // Send confirmation mail
            try {
                Mail::to($data["email"])->send(new Confirmation($user, $callback_url, $confirmation_token));
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
            }

            $response['user'] = $user;
            return $this->success($response);
        }
        catch (\Throwable $ex)
        {
            return $this->server_error($ex);
        }
    }
}
