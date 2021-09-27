<?php

namespace App\Http\Controllers;

use App\Enums\TokenTypes;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthToken as AuthTokenResource;
use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * Create a new user and give a new token
     *
     * @param RegisterRequest $request
     * @return AuthTokenResource
     */

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $user->profile()->create($request->validated());
        $emailVerificationToken = $user->getToken(TokenTypes::EMAIL_VERIFY, config('app.email_verification_token_lifetime'));
        $user->notify(new EmailVerification($emailVerificationToken));
        $token = $user->createToken($request->userAgent());
        return new AuthTokenResource($token);
    }

    /**
     * Give a new token if "email" and "password" are exist in DB
     *
     * @param LoginRequest $request
     * @return AuthTokenResource|Application|ResponseFactory|\Illuminate\Http\Response
     */

    public function logIn(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return response(['message' => 'Wrong email or password'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken($request->userAgent());
        return new AuthTokenResource($token);
    }

    /**
     * Delete a token
     *
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|\Illuminate\Http\Response
     */

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }

}
