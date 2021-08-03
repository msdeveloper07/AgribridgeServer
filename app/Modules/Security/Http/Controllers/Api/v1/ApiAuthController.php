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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Hash;
use Mail; 
use Session;
use Illuminate\Support\Facades\Redirect;

use App\Mail\VerifyMail;
use Illuminate\Support\Facades\View;

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
                'message' => $validator->messages()->first()
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

         $token = Str::random(64);
          $mobile = $request->countryCode.' '.$request->mobile_number;
        //Request is valid, create new user
        $user = User::create([
            'user_name' => $request->user_name,
            'org_id' => $orgId,
            'mobile_number' => $mobile,
            'designation' => $request->designation,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'token' => $token
        ]);

         $userId = $user->usr_id;

         $userData = array(
            'user_id' =>$userId,
            'token' =>$token
         );
         $organization = UserRoles::create([
            'usr_id' => $userId,
            'rol_id' => $roleId,
          ]);
      // echo"<pre>"; print_r($userData); die;

        //Mail::to($request->email)->send(new VerifyMail($userData));
         Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Email Verification Mail');
          });


        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $data
        ], Response::HTTP_OK);


    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        $verifyUser = User::where('token', $token)->first();
        
        $message = 'Sorry your email cannot be identified.';
        //echo"<pre>"; print_r($verifyUser); die;
        if(!empty($verifyUser) ){
            $user = $verifyUser->is_email_verified;
            if($user == 0) {
                $verifyUser->is_email_verified = 1;
                DB::table('users')->where('token', $token)->update(['is_email_verified' => 1]);
                //$verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return redirect('http://127.0.0.1:8000/login')->with('warning', "Sorry your email cannot be identified.");
        }
  
      return redirect('http://127.0.0.1:8000/login')->with('status', $status);
      //return redirect()->route('login')->with('message', $message);
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

    public function forgot_password(Request $request)
    {

       //echo "<pre>"; print_r($request); die;
        $input = $request->all();       
        $request->validate([
        'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

          DB::table('password_resets')->insert(
              ['email' => $request->email, 'token' => $token]
          );

          Mail::send('email.resetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password Notification');
          });
          
          //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'We have e-mailed your password reset link!',
        ], Response::HTTP_OK);

        

    }

   public function change_password(Request $request)
    {

        $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:8|confirmed',
              'password_confirmation' => 'required'
          ]);
  

          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
  
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
  
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
 
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
  
          return redirect('login')->with('message', 'Your password has been changed!');
      
        

      
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
                deleteFileFromStorage($path, explode("storage/images/user/", $user->user_image_url)[1]);
            }
        }
        unset($data['log_file']);

        $update = User::where('usr_id', $user->usr_id)->update($data);
        return response()->json([
            'success' => true,
            'message' => "Data Save.",
            'data' => tokentAuthentication()
        ], Response::HTTP_OK);
    }


  
}
