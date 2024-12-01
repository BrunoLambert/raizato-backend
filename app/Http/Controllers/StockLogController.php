<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\CreateStockLogRequest;
use App\Models\StockLog;
use App\Http\Requests\UpdateStockLogRequest;
use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = StockLog::with([
            "stock" =>
            [
                "product:id,name,category_id,supplier_id" => ["category:id,name", "supplier:id,name"]
            ]
        ]);
        if ($user->role === UserRoleEnum::Common) {
            $query->where("user_id", $user->id);
        }
        $query = $query->paginate(15);

        return $this->sendResponse($query, "Ok");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateStockLogRequest $request)
    {
        try {
            $data = $request->all();
            $stocklog = [
                "quantity" => $data["quantity"],
                "type" => $data["type"],
                "stock_id" => 0,
                "user_id" => $request->user()->id
            ];

            $product = Product::find($data["product_id"]);
            if (!isset($product)) {
                return $this->sendError("Not Found", "Product was not found", 404);
            }

            $stock = $product->stock()->get()->first();
            $previousQuantity = 0;

            if (!isset($stock)) {
                $stock = new Stock(["quantity" => $stocklog["quantity"], "product_id" => $product->id]);
            } else {
                $previousQuantity = $stock->quantity;
                $plusTypes = ["purchase", "return"];
                $stock->quantity = in_array($stocklog["type"], $plusTypes) ?
                    $previousQuantity + $stocklog["quantity"] :
                    $previousQuantity - $stocklog["quantity"];
            }
            $stock->save();
            $stocklog["stock_id"] = $stock->id;

            $newStocklog = StockLog::create($stocklog);
            if (!isset($newStocklog)) {
                $stock->update(["quantity" => $previousQuantity]);
                return $this->sendError("Server Error", "Log was not created", 500);
            }

            return $this->sendResponse($stocklog, "Registered successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockLogRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = StockLog::find($id);
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
            $toDelete = StockLog::find($id);

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
