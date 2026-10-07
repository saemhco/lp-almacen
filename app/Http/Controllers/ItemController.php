<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();
        return $this->successResponse($items, 'Items retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:items,name',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'location_id' => 'required|exists:locations,id',
            ]);

            $item = Item::create($validatedData);

            return $this->successResponse($item, 'Item created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation Error 123', 422);
        } catch (\Exception $e) {
            return $this->errorResponse("Server Error", $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($item)
    {
        try {
            $data = Item::find($item);
            if (!$data) {
                return $this->errorResponse('Item not found', 404);
            }
            return $this->successResponse($item, 'Item retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse("Server Error", $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:items,name,' . $item->id,
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'quantity' => 'sometimes|required|integer|min:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'supplier_id' => 'sometimes|required|exists:suppliers,id',
                'location_id' => 'sometimes|required|exists:locations,id',
            ]);

            $item->update($validatedData);

            return $this->successResponse($item, 'Item updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse("Server Error", $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return $this->successResponse(null, 'Item deleted successfully');
    }
}
