<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        
        return view('admin.dashboard.index');
    }


    public function authentification(Request $request)
    {
       $email = $request->email;
       $password = $request->password;

        if($email == null){
            toastr()->error("L'adresse mail est obligatoire");
            return redirect()->back();
        }
        if($password == null){
            toastr()->error("Le mot de passe est obligatoire");
            return redirect()->back();
        }

        $user = User::whereEmail($email)->first();
        if($user == null){
            toastr()->error("Cette adresse mail n'est pas reconnue");
            return redirect()->back()->withInput();
        }
        if(! Hash::check($password, $user->password)){
            toastr()->error("Le mot de passe est incorrect");
            return redirect()->back()->withInput();
        }

        if($user->status == 0){
            toastr()->error("Votre compte n'est pas disponible, veuillez contacter l'administrateur principal");
            return redirect()->back()->withInput();
        }

        Auth::login($user);
        toastr()->success("Connexion réussie");
        return redirect()->route('dashboard.index');

    }
}
