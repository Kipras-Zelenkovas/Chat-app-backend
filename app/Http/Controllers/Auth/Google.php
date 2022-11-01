<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Google extends Controller
{
    private $default_provider = 'Google';
    private $default_role = 1;
    private $default_ban = 0;
    
    public function redirect(){
        return Socialite::class::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function callback(){
        try{
            $google_user = Socialite::class::driver('google')->stateless()->user();

            $user = User::updateOrCreate([
                'uuid'      => $google_user->id,
                'name'      => $google_user->name,
                'surname'   => $google_user->name,
                'username'  => $google_user->name,
                'email'     => $google_user->email,
                'provider'  => $this->default_provider,
                'role_id'   => $this->default_role,
                'banned'    => $this->default_ban,
            ]);

            $user->save();

            Auth::login($user);

            return response()->json([
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }catch(\Exception $th){
            return response()->json($th->getMessage());
        }
    }
}
