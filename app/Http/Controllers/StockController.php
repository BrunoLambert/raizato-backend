<?php

namespace App\Http\Controllers;

use App\Enums\StockLogTypeEnum;
use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\StockLog;
use Exception;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result["data"] = Stock::with(["product:id,name,category_id,supplier_id", "product.category", "product.supplier"])->get();
        $result["size"] = count($result["data"]);

        return response($result, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request)
    {
        try {
            $input = $request->all();
            $newData = Stock::create($input);
            if (!isset($newData)) {
                return response("It was not possible to create it");
            }

            $result["message"] = "Registered successfully";
            $result["data"] = $newData;

            $stocklog = [
                "quantity" => $newData->quantity,
                "type" => StockLogTypeEnum::Creation,
                "stock_id" => $newData->id,
                "user_id" => $request->user()->id
            ];
            StockLog::create($stocklog);

            return response($result, 201);
        } catch (\Throwable $th) {
            $error = isset($th->errorInfo) ? $th->errorInfo : $th;
            return response($error, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockRequest $request, int $id)
    {
        try {
            $toUpdateData = $request->all();
            $toUpdate = Stock::find($id);
            if (!isset($toUpdate)) {
                return response("Not found", 404);
            }
            return response(StockLogTypeEnum::cases());

            if ($toUpdate->update($toUpdateData)) {
                $result["message"] = "Updated successfully";
                $result["data"] = $toUpdate;

                $stocklog = [
                    "quantity" => $toUpdateData["quantity"],
                    "type" => StockLogTypeEnum::Adjustment,
                    "stock_id" => $toUpdate->id,
                    "user_id" => $request->user()->id
                ];
                StockLog::create($stocklog);

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
            $toDelete = Stock::find($id);

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
