<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRquest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Twilio\Rest\Client;
use Twilio\Rest\Client;

class userController extends Controller
{
    //
    public function register(RegisterRquest $request)
    {
        $code = mt_rand(000001, 999999);
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'code' => $code,
            'verified' => 'Not Verified',
        ]);
        if($user){
            $token = $user->createToken('token', [$user->id]);
        }
        //if ($this->act($request, $code)->sid) { //sms works only for my number 01062153292
            $response = [
                'status' => "success",
                'msg' => 'Account Created',
                'data' => $user,
                'token' => $token->plainTextToken,
            ];
            return response()->json($response);
        //}
    }
    public function login(Request $request)
    {
        if (!Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            return response()->json(['status' => "fail", 'msg' => 'wrong credentials']);
        }
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        if($user->verified == 'Verified')
        $token = $user->createToken('token', [$user->id]);
        return response()->json(['status' => "success",'msg' => "", 'data' => $user, 'token' => $token->plainTextToken]);
    }

    public function verify(Request $request){
        $user = User::where('phone',$request->phone)->where('code',$request->code)->first();
        if($user){
            $user->update(['verified'=>'Verified']);
            $response = [
                'status' => "success",
                'msg' => 'Account Verified',
                'data' => $user,
            ];
        }else{
            $response = [
                'status' => "success",
                'msg' => 'Wrong phone or code',
                'data' => '',
            ];
        }
        return response()->json($response);
    }

    function act($request, $code)
    {
        $sid = "AC96eaa4e191bdbb9a5e59bccf30f0f6a8"; // Your Account SID from www.twilio.com/console
        $token = "74d62a1a861fb308ec48d31d4060f3f1"; // Your Auth Token from www.twilio.com/console
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            '+2' . $request->phone, // Text this number
            [
                'from' => '+18508189216', // From a valid Twilio number
                'body' => __('msg.your Blue Development account activation code is') . $code
            ]
        );
        return $message;
    }
}
