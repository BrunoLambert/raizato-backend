<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result["data"] = Product::with(["category:id,name", "supplier:id,name"])->get();
        $result["size"] = count($result["data"]);

        return response($result, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $input = $request->all();
            $newData = Product::create($input);
            if (!isset($newData)) {
                return response("It was not possible to create it");
            }

            $result["message"] = "Registered successfully";
            $result["data"] = $newData->name;

            return response($result, 201);
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = Product::find($id);
            if (!isset($toUpdate)) {
                return response("Not found", 404);
            }

            if ($toUpdate->update($toUpdateData)) {
                $result["message"] = "Updated successfully";
                $result["data"] = $toUpdate->name;

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
            $toDelete = Product::find($id);

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
