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
        $result = Stock::with(["product:id,name,category_id,supplier_id,minimum_stock", "product.category", "product.supplier"])->paginate(15);

        return $this->sendResponse($result, "Ok");
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
                return $this->sendError("Server Error", "Not possible to create", 500);
            }

            $stocklog = [
                "quantity" => $newData->quantity,
                "type" => StockLogTypeEnum::Creation,
                "stock_id" => $newData->id,
                "user_id" => $request->user()->id
            ];
            StockLog::create($stocklog);

            return $this->sendResponse($newData, "Registered successfully");
        } catch (\Throwable $th) {
            return $this->sendError($th, "Server Error", 500);
        }
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
                return $this->sendError("Not Found", "Not Found", 404);
            }

            if ($toUpdate->update($toUpdateData)) {
                $stocklog = [
                    "quantity" => $toUpdateData["quantity"],
                    "type" => StockLogTypeEnum::Adjustment,
                    "stock_id" => $toUpdate->id,
                    "user_id" => $request->user()->id
                ];
                StockLog::create($stocklog);

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
            $toDelete = Stock::find($id);

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
