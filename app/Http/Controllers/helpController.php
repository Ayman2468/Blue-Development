<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class helpController extends Controller
{
    //
    public function stats(){
            if (Cache::has('userNumber'))
            {
                $usersNumber = Cache::get('userNumber');
                $usersNumber = User::all()->count();
            }
            else
            {
                $usersNumber = User::all()->count();
            }
        
            Cache::put('userNumber',$usersNumber);

        if (Cache::has('postsNumber'))
            {
                $postsNumber = Cache::get('postsNumber');
                $postsNumber = Post::all()->count();
            }
            else
            {
                $postsNumber = User::all()->count();
            }
        
            Cache::put('postsNumber',$postsNumber);

        if (Cache::has('zeropostusersNumber'))
            {
                $zeropostusersNumber = Cache::get('zeropostusersNumber');
                $userids = User::select('id')->get()->toArray();
                $postuserids = Post::select('user_id')->get()->toArray();
                if($userids == null) $userids[0] = [0];
                if($postuserids == null) $postuserids[0] = [0];
                $arr = array_diff($userids[0],$postuserids[0]);
                $zeropostusersNumber = User::whereIn('id',$arr)->get()->count();
            }
            else
            {
                $userids = User::select('id')->get()->toArray();
                $postuserids = Post::select('user_id')->get()->toArray();
                $arr = array_diff($userids,$postuserids);
                $zeropostusersNumber = User::whereIn('id',$arr)->get()->count();
            }
        
            Cache::put('zeropostusersNumber',$zeropostusersNumber);
        $response = [
            'status' => "success",
            'msg' => "",
            'data' =>  [
                    'Number of Users' => $usersNumber,
                    'Number of Posts' => $postsNumber,
                    'Number of Users with 0 Posts' => $zeropostusersNumber
            ]
        ];
        return response()->json($response);
    }

    public static function apiCall(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://randomuser.me/api/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $resultResponse = curl_exec($ch);
        $results = json_decode($resultResponse, true);
        return $results;//console/kernel runs it every 6 hours
    }
}
