<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStockLogRequest;
use App\Models\StockLog;
use App\Http\Requests\StoreStockLogRequest;
use App\Http\Requests\UpdateStockLogRequest;
use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Mockery\Undefined;

class StockLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result["data"] = StockLog::with(["stock:id,product_id,quantity", "stock" => ["product:id,name"]])->get();
        $result["size"] = count($result["data"]);

        return response($result, 200);
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
                return response("Not found", 404);
            }

            $stock = $product->stock()->get()->first();

            if (!isset($stock)) {
                $stock = new Stock(["quantity" => $stocklog["quantity"], "product_id" => $product->id]);
            } else {
                $plusTypes = ["purchase", "return"];
                $stock->quantity = in_array($stocklog["type"], $plusTypes) ?
                    $stock->quantity + $stocklog["quantity"] :
                    $stock->quantity - $stocklog["quantity"];
            }
            $stocklog["stock_id"] = $stock->id;

            $newStocklog = StockLog::create($stocklog);
            if (!isset($newStocklog)) {
                return response("It was not possible to create it");
            }

            $stock->save();
            $result["message"] = "Registered successfully";
            $result["data"] = $stocklog;

            return response($result, 201);
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockLog $stockLog)
    {
        //
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
                return response("Not found", 404);
            }

            if ($toUpdate->update($toUpdateData)) {
                $result["message"] = "Updated successfully";
                $result["data"] = $toUpdate;

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
            $toDelete = StockLog::find($id);

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
