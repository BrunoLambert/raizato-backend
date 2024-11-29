<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            DB::table("personal_access_tokens")->where("tokenable_id", $authUser->id)->delete();

            $success['token'] =  $user->createToken('RaizatoApp')->plainTextToken;
            $success['name'] =  $user->fullname;

            return $this->sendResponse($success, 'Login Success');
        }

        return response(["message" => "Login Failed"], 403);
    }

    /**
     * Logout the user    
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        DB::table("personal_access_tokens")->where("tokenable_id", $request->user()->id)->delete();

        return response("Logout Completed", 200);
    }

    /**
     * Check if there is an admin
     */
    public function checkAdmin()
    {
        try {
            $adminUser = User::where('role', 'admin')->first();

            return response()->json([
                "check" => isset($adminUser)
            ], 200);
        } catch (\Throwable $th) {
            return response($th->errorInfo, 500);
        }
    }

    /**
     * Init admin user
     */
    public function initAdmin(StoreAdminRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 'admin';

        $new_user = User::create($input);

        $success['token'] =  $new_user->createToken('RaizatoApp')->plainTextToken;
        $success['name'] =  $new_user->fullname;

        return $this->sendResponse($success, "Admin registered successfully");
    }
}
