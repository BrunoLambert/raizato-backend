<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchSupplierRequest;
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
        $result = Supplier::paginate(15);

        return $this->sendResponse($result, "Ok");
    }

    /**
     * Search depending on the search param.
     */
    public function search(SearchSupplierRequest $request)
    {
        $input = $request->all();
        $result = Supplier::where('name', 'LIKE', '%' . $input['search'] . '%')->take(10)->get(["name", "id"]);

        return $this->sendResponse($result, "Search completed");
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
    public function update(UpdateSupplierRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = Supplier::find($id);
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
            $toDelete = Supplier::find($id);

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
