<?php

namespace App\Modules\Security\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Modules\Security\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    public function welcome()
    {
        return view("Security::welcome");
    }

    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('mobile_number', 'email', 'password');
        $validator = Validator::make($data, [
            'mobile_number' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5|max:15'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()->first()
            ], Response::HTTP_OK);
        }

        //Request is valid, create new user
        $user = User::create([
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:5|max:15'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'email_verification' => auth()->user()->hasVerifiedEmail(),
            'token' => $token,
            'user_data' => auth()->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->auth_token = $request->bearerToken();

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->auth_token);
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return 0;
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}
