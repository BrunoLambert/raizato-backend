<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Authenticate the user login
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->all();

        if (Auth::attempt($credentials)) {
            $authUser = Auth::user();
            $user = User::find($authUser->id);

            $success['token'] =  $user->createToken('RaizatoApp')->plainTextToken;
            $success['name'] =  $user->fullname;

            return $this->sendResponse($success, 'Login Success');
        }
    }

    /**
     * Logout the user    
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response("Logout Completed", 200);
    }
}
