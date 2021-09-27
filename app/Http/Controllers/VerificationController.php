<?php

namespace App\Http\Controllers;

use App\Enums\TokenTypes;
use App\Notifications\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    /**
     * Verify given email
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */

    public function verify(Request $request)
    {
        $request->user()->markEmailAsVerified();
        return response()->noContent();
    }

    /**
     * Resend new verification email
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */

    public function resend(Request $request)
    {
        $user = $request->user();
        $emailVerificationToken = $user->getToken(TokenTypes::EMAIL_VERIFY, config('app.email_verification_token_lifetime'));
        $user->notify(new EmailVerification($emailVerificationToken));
        return response(['message' => 'The email has been sent']);
    }
}
