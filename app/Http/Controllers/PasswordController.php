<?php

namespace App\Http\Controllers;

use App\Enums\TokenTypes;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as Status;

class PasswordController extends Controller
{
    /**
     * Send confirmation email
     * @param ChangePasswordRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function requestPasswordChange(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $password = $request->password;
        $resetPasswordToken = $user->getToken(TokenTypes::PASSWORD_RESET, config('app.password_reset_token_lifetime'), ['password' => $password]);
        $user->notify(new PasswordResetNotification($resetPasswordToken));
        return response(['message' => 'Check your mailbox to confirm password resetting']);
    }

    /**
     * Confirm email is registered, send email
     * @param ForgotPasswordRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = $user->getToken(TokenTypes::PASSWORD_RECOVER, config('app.password_recover_token_lifetime'),);
            $user->notify(new PasswordRecoveryNotification($token));
        }
        return response(['message' => 'You will receive a mail to recover the password if this is proper email']);
    }

    /**
     * Recover password, set new one
     * @param ChangePasswordRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function recover(ChangePasswordRequest $request)
    {
        $request->user()->update($request->validated());
        return response(['message' => 'You can use new password to login']);
    }

    /**
     * Reset password
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request->user()->update(['password' => $request->password]);
        return response(['message' => 'New password has been set']);
    }
}
