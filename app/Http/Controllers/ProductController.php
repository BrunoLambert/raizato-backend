<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchProductRequest;
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
        $result = Product::with(["category:id,name", "supplier:id,name"])->paginate(15);

        return $this->sendResponse($result, "Ok");
    }

    /**
     * Search depending on the search param.
     */
    public function search(SearchProductRequest $request)
    {
        $input = $request->all();
        $result = Product::where('name', 'LIKE', '%' . $input['search'] . '%');

        if ($input["completed"]) {
            $result = $result->with(['category:id,name', 'supplier:id,name']);
        }

        $result = $result->take(10)->get($input['completed'] ? ["name", "id"] : '');

        return $this->sendResponse($result, "Search completed");
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
                return $this->sendError("Server Error", "Not possible to create", 500);
            }

            return $this->sendResponse($newData, "Registered successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
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
                return $this->sendError("Not Found", "Not Found", 404);
            }

            if ($toUpdate->update($toUpdateData)) {
                return $this->sendResponse($toUpdate, "Updated successfully");
            }

            throw new Exception("It was not possible to complete the update");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
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
                return $this->sendError("Not Found", "Not Found", 404);
            }
            if ($toDelete->delete()) {
                return $this->sendResponse($toDelete, "Deleted successfully");
            }

            throw new Exception("It was not possible to complete the delete");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }
}
