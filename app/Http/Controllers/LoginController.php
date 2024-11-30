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

        return $this->sendError(false, "Login Failed", 403);
    }

    /**
     * Logout the user    
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            DB::table("personal_access_tokens")->where("tokenable_id", $request->user()->id)->delete();

            return $this->sendResponse(true, "Logout Completed");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }

    /**
     * Check if there is an admin
     */
    public function checkAdmin()
    {
        try {
            $adminUser = User::where('role', 'admin')->first();

            return $this->sendResponse(isset($adminUser), "Ok");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }

    /**
     * Init admin user
     */
    public function initAdmin(StoreAdminRequest $request)
    {
        try {
            $usersCount = User::count();
            if ($usersCount > 0) {
                return $this->sendError("Invalid Request", "Not Found", 404);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['role'] = 'admin';

            $new_user = User::create($input);

            $success['token'] =  $new_user->createToken('RaizatoApp')->plainTextToken;
            $success['name'] =  $new_user->fullname;

            return $this->sendResponse($success, "Admin registered successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }
}
