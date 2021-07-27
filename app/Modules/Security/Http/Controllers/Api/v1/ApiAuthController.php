<?php

namespace App\Modules\Security\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Modules\Security\Models\User;
use App\Modules\Security\Models\Organizations;
use App\Modules\Security\Models\UserRoles;
use App\Modules\Security\Models\Roles;
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
        $data = $request->only('user_name', 'user_type','register_company', 'designation', 'mobile_number','email', 'password', 'privacyPolicy');
        
        $validator = Validator::make($data, [
            'user_name' => 'required|string',
            'user_type' => 'required|string',
            'register_company' => 'required|string',
            'designation' => 'required|string',
            'mobile_number' => 'required|digits:10',            
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:5|max:15',            
            'privacyPolicy' => 'required|string'

        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()->first()
            ], Response::HTTP_OK);
        }
        
        
         $organization = Organizations::create([
            'org_name' => $request->register_company,
            'app_id' => '1',
          ]);


         $orgId = $organization->id; 

         $role = Roles::create([
            'org_id' => $orgId,
            'role_name' => $request->user_type,
          ]);

         $roleId = $role->id; 


        //Request is valid, create new user
        $user = User::create([
            'user_name' => $request->user_name,
            'org_id' => $orgId,
            'mobile_number' => $request->mobile_number,
            'designation' => $request->designation,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

         $userId = $user->id;
         $organization = UserRoles::create([
            'usr_id' => $userId,
            'rol_id' => $roleId,
          ]);


       $token = JWTAuth::fromUser($user);     

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

    public function forgot_password(Request $request)
    {

        $input = $request->all();
        echo"<pre>"; print_r($input); die;
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

   public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }
    
}
