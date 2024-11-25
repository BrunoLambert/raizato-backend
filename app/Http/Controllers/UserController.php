<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Check if there is an admin
     */
    public function initAdmin()
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $response['users'] = User::all();
        $response['size'] = count($response['users']);

        return $this->sendResponse($response, "Ok");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $new_user = User::create($input);

            $success['token'] =  $new_user->createToken('RaizatoApp')->plainTextToken;
            $success['name'] =  $new_user->fullname;

            return $this->sendResponse($success, "User Register successfully");
        } catch (\Throwable $th) {
            return response($th->errorInfo, 500);
        }
    }

    /**
     * Update self user data
     */
    public function edit(UpdateUserRequest $request)
    {
        try {
            $userID = $request->user()->id;
            $newData = $request->all();

            $updatedUser = User::find($userID)->update($newData);

            $success['token'] =  $updatedUser->createToken('RaizatoApp')->plainTextToken;
            $success['name'] =  $updatedUser->fullname;

            return $this->sendResponse($success, "User updated successfully");
        } catch (\Throwable $th) {
            return response($th->errorInfo, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $authUser = $request->user();
            $toUpdateUser = User::find($id);
            $toUpdateData = $request->all();

            if ($authUser->id !== $id && $toUpdateUser->role === "admin") {
                return response("Not authorizated", 403);
            }

            $toUpdateUser->update($toUpdateData);

            return $this->sendResponse(
                ["fullname" => $toUpdateUser->fullname],
                "User updated successfully"
            );
        } catch (\Throwable $th) {
            return response($th->errorInfo, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $id, Request $request)
    {
        try {
            $authUser = $request->user();
            $toDeleteUser = User::find($id);

            if ($authUser->id !== $id && $toDeleteUser->role === "admin") {
                return response("Not authorizated", 403);
            }

            $toDeleteUser->delete();

            return $this->sendResponse(
                ["fullname" => $toDeleteUser->fullname],
                "User deleted successfully"
            );
        } catch (\Throwable $th) {
            return response($th->errorInfo, 500);
        }
    }
}
