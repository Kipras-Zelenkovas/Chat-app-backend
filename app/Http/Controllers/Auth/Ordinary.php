<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class Ordinary extends Controller
{

    private $default_role = 1;
    private $default_provider = "Default";
    private $default_banned = 0;

    public function register(RegisterUser $request){
        try {
            $request->validated();

            $user = User::create([
                'uuid'      => Str::uuid(),
                'name'      => $request->name,
                'surname'   => $request->surname,
                'username'  => $request->username,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role_id'   => $this->default_role,
                'provider'  => $this->default_provider,
                'banned'    => $this->default_banned
            ]);

            $user->save();

            return response()->json("User created", 201);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function login(LoginUser $request){
        try {
            $request->validated();

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $request->session()->regenerate();

                return response()->json([
                    "token" => $request->user()->createToken("API TOKEN")->plainTextToken,
                    "user"  => $request->user(),
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }

    public function logout(Request $request){
        try {
            $request->user()->tokens()->delete();

            Auth::guard("web")->logout();

            $request->session()->invalidate();

            return response()->json("Logout successful");
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function forgot_password(Request $request){
        try {
            $request->validate([
                'email' => 'required|email'
            ]);
    
            $status = Password::sendResetLink(
                $request->only("email")
            );
    
            return $status === Password::RESET_LINK_SENT
                ? response()->json("Reset link sent")
                : response()->json("Something went wrong");
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    
    public function reset_password(Request $request){
        try {
            $request->validate([
                'email'             => 'required|email',
                'password'          => 'required|min:8|max:25',
                'confirm_password'  => 'required|min:8|max:25',
                'token'             => 'required'
            ]);
    
            $status = Password::reset(
                $request->only('email', 'password', 'confirm_password', 'token'),
                function($user, $password){
                    $user->forceFill([
                        'password'  => Hash::make($password)
                    ]);
    
                    $user->save();
    
                    event(new PasswordReset($user));
                }
            );
    
            return $status === Password::PASSWORD_RESET
                ? response()->json('Password successfuly reset')
                : response()->json('Something went wrong reseting password. Please try again');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
