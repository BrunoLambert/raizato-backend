<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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

            $success['name'] =  $new_user->fullname;
            $success["message"] = "User Register successfully";

            return response($success, 201);
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
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

            if (User::find($userID)->update($newData)) {
                return response("User updated successfully", 202);
            }
            throw new Exception("It was not possible to complete the update");
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
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

            if ($authUser->id !== $id && $toUpdateUser->role === UserRoleEnum::Admin) {
                return response("Not authorizated", 403);
            }

            if ($toUpdateUser->update($toUpdateData)) {
                return response("User updated successfully", 202);
            }

            throw new Exception("It was not possible to complete the update");
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, Request $request)
    {
        try {
            $authUser = $request->user();
            $toDeleteUser = User::find($id);

            if (!isset($toDeleteUser)) {
                return response("Not found", 404);
            }

            if ($authUser->id !== $id && $toDeleteUser->role === UserRoleEnum::Admin) {
                return response("Not authorizated", 403);
            }

            if ($toDeleteUser->delete()) {
                DB::table("personal_access_tokens")->where("tokenable_id", $toDeleteUser->id)->delete();
                return response("User deleted successfully", 202);
            }

            throw new Exception("It was not possible to complete the delete");
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }
}
