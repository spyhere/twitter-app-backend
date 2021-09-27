<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class EnsureEmailIsNotVerified
{
    /**
     * Is email already verified?
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response(['message' => 'Already verified'], Response::HTTP_CONFLICT);
        }

        return $next($request);
    }
}
