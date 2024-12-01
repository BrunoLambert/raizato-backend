<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchCategoryRequest;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Exception;

use function Laravel\Prompts\error;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Category::paginate(15);

        return $this->sendResponse($result, "Ok");
    }

    /**
     * Search depending on the search param.
     */
    public function search(SearchCategoryRequest $request)
    {
        $input = $request->all();
        $result = Category::where('name', 'LIKE', '%' . $input['search'] . '%')->take(10)->get(["name", "id"]);

        return $this->sendResponse($result, "Search completed");
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $input = $request->all();
            $newData = Category::create($input);
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
    public function update(UpdateCategoryRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = Category::find($id);
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
            $toDelete = Category::find($id);

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
