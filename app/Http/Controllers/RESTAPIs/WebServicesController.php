<?php

namespace App\Http\Controllers\RESTAPIs;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage;
use Hash;
use Illuminate\Validation\Rule;

class WebServicesController extends Controller
{
    /**
     *
     * Create Token
     *
     * @author Bipin Parmar
     *
     * @return Token
     */

    private function createToken($user_id) {

        $user = User::where('id', $user_id)->first();

        $token = $user->createToken('MyApp')->accessToken;
        $final_token = 'Bearer '.$token;

        return $token;
    }

    /**
	   *
	   * User Register
	   *
	   * @author Bipin Parmar
	   *
	   * @return JSON with User Register Details
	   */

	public function register(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            $errors = $validator->errors();
            return ResponseMessage::error($errors->first());
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $this->createToken($user->id);

        return ResponseMessage::successWithToken('User Register Successfully', $user, $token);

    }


    /**
     *
     * User Login with email or phone number
     *
     * @author Bipin Parmar
     *
     * @return JSON with User Login
     */

    public function logIn(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if($validator->fails()){
            $errors = $validator->errors();
            return ResponseMessage::error($errors->first());
        }

        if(User::where('email', $request->email)->exists()){
            $user = User::where('email',$request->email)->first();

            $token = $this->createToken($user->id);

            $pass = $user->password;
            if(Hash::check($request->password,$pass)) {
                return ResponseMessage::successWithToken("Login Successfully",$user, $token );
            }else{
                return ResponseMessage::error('Please Enter Valid Credentials');
            }
        }else {
            return ResponseMessage::error('User Not Found');
        }

    }



    /**
     *
     * User Logout
     *
     * @author Bipin Parmar
     *
     * @return JSON
     */

    public function logout()
    {
        $user = (auth('api')->user()->token());
        $user->revoke();

        return ResponseMessage::success("Logged out", true);
    }
}
