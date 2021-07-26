<?php

namespace App\Modules\Security\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Modules\Security\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;

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
                'message' => $validator->messages()->first()
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
            return response()->json(['message' => $validator->messages()->first()], 200);
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
            'message' => 'Login successfully',
            'email_verification' => auth()->user()->hasVerifiedEmail(),
            'token' => $token,
            'data' => auth()->user(),
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
        $user = tokentAuthentication();
        return response()->json([
            'success' => true,
            'message' => "Success",
            'data' => $user
        ]);
    }

    public function edit_user(Request $request)
    {
        $user = tokentAuthentication();
        $data = $request->except('mandal');
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'user_name' => 'required',
            'mobile_number' => 'required'
        ], [
            'user_name.required' => "The User Name field is required.",
            'mobile_number.required' => "The Mobile Number field is required.",
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ], Response::HTTP_OK);
        }
        $path = 'public/images/user/';

        if ($request->log_file) {
            // file upload
            $fileName = (string) Str::upper(Str::random(rand(1, 10))) . rand(1, 9999);
            $newFileName = base64File($fileName, $request->log_file, $path);
            $log_file_ = $newFileName;
            $data['user_image_url'] = "storage/images/user/" . $log_file_;

            if($user->user_image_url){
                deleteFileFromStorage($path, $user->user_image_url);
            }
        }
        unset($data['log_file']);

        $update = User::where('usr_id', $user->usr_id)->update($data);
        return response()->json([
            'success' => true,
            'message' => "Data Save.",
            'data' => $user
        ], Response::HTTP_OK);
    }
}
