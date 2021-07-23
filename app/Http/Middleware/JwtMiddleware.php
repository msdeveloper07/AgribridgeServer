<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user)
                throw new Exception('User Not Found');
            if ($user->deleted_at != NULL) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is deleted, Please contact to admin for restore'
                ], Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Token'
                ], Response::HTTP_FORBIDDEN);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session has been expired.'
                ], Response::HTTP_FORBIDDEN);
            } else {
                if ($e->getMessage() === 'User Not Found') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Session has been expired.'
                    ], Response::HTTP_FORBIDDEN);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'You must provide valid Authentication token'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        return $next($request);
    }
}
