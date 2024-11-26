<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result["data"] = Supplier::all();
        $result["size"] = count($result["data"]);

        return response($result, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        try {
            $input = $request->all();
            $newData = Supplier::create($input);
            if (!isset($newData)) {
                return response("It was not possible to create it");
            }

            $result["message"] = "Registered successfully";
            $result["data"] = $newData->name;

            return response($result, 201);
        } catch (\Throwable $th) {
            return response(['error' => $th], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = Supplier::find($id);
            if (!isset($toUpdate)) {
                return response("Not found", 404);
            }

            if ($toUpdate->update($toUpdateData)) {
                $result["message"] = "Updated successfully";
                $result["supplier"] = $toUpdate->name;

                return response($result, 202);
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
    public function destroy(int $id)
    {
        try {
            $toDelete = Supplier::find($id);

            if (!isset($toDelete)) {
                return response("Not found", 404);
            }
            if ($toDelete->delete()) {
                return response("Deleted successfully", 202);
            }

            throw new Exception("It was not possible to complete the delete");
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }
}
