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
        $response = User::paginate(15);

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

            return $this->sendResponse($new_user, "User Register successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
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
                return $this->sendResponse($newData, "User updated successfully");
            }
            throw new Exception("It was not possible to complete the update");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
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
                return $this->sendError("", "Not authorizated", 403);
            }

            if ($toUpdateUser->update($toUpdateData)) {
                return $this->sendResponse($toUpdateData, "User updated successfully");
            }

            throw new Exception("It was not possible to complete the update");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
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
                return $this->sendError("Not found", "Not found", 404);
            }

            if ($authUser->id !== $id && $toDeleteUser->role === UserRoleEnum::Admin) {
                return $this->sendError("Auth Failed", "Not authorizated", 403);
            }

            if ($toDeleteUser->delete()) {
                DB::table("personal_access_tokens")->where("tokenable_id", $toDeleteUser->id)->delete();
                return $this->sendResponse("User deleted successfully", 202);
            }

            throw new Exception("It was not possible to complete the delete");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }
}
