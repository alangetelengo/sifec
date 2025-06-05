<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function login(Request $request){

        $rules = [
            "email"=>"required|email",
            "password"=>"required"
        ];


        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>collect($validator->errors())->flatten()
            ]);
        }

        $user = User::where("email",$request->email)->first();
        if($user == null){
            return response()->json([
                "code"=>"180",
                "message"=>["Utilisateur non reconnu"]
            ]);
        }

        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                "code"=>"180",
                "message"=>["Mot de passe incorrect"]
            ]);
        }
       

        $auth = Auth::login($user);
        $token = Auth::user()->createToken('access_token')->accessToken;
        return response()->json([
            "code"=>"200",
            "message"=>["Connexion réussie"],
            "user"=>$user,
            "token"=>$token
        ]);
    }
}
