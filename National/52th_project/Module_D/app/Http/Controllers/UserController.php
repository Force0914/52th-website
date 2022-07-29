<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $result = User::all()->where("email",$request['email'])->toArray();
        if ($result != [] && Hash::check($request['password'],strval($result[key($result)]["password"]), ['rounds' => 12])){
            $result[key($result)]["profile_image"] = "http://".$_SERVER["HTTP_HOST"] . $result[key($result)]['profile_image'];
            $result[key($result)]["access_token"] = hash("sha256",$result[key($result)]["email"]);
            unset($result[key($result)]["password"]);
            return response()->json(RESTfulAPI(true,"",$result[key($result)]));
        }else{
            return response()->json(RESTfulAPI(false,"MSG_INVALID_LOGIN",""))->setStatusCode(403);
        }
    }
}

function RESTfulAPI($success,$message,$data){
    return [
        "success" => $success,
        "message" => $message,
        "data" => $data
    ];
}
