<?php

namespace App\Http\Middleware;

use App\Enums\TokenTypes;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class DecryptToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $operation
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $operation)
    {
        try {
            $claims = decrypt($request->token);

            $validation = Validator::make($claims, [
                'sub' => ['required', 'integer', 'exists:users,id'],
                'type' => ['required', 'string', Rule::in([$operation])],
                'exp' => ['required', 'integer', 'min:' . Carbon::now()->timestamp],
                'password' => ['required_if:type,' . TokenTypes::PASSWORD_RESET]
            ]);

            if ($validation->fails()) {
                throw new DecryptException();
            }

        } catch(DecryptException $exception) {
            return response([
                'message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }
        $user = User::find($claims['sub']);
        auth()->setUser($user);
        if (isset($claims['password'])) {
            $request->request->set('password', $claims['password']);
        }

        return $next($request);
    }
}
