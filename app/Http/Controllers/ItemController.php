<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/items',
        summary: 'List items',
        tags: ['Items'],
        responses: [
            new OA\Response(response: 200, description: 'Items retrieved successfully'),
        ]
    )]
    public function index()
    {
        $items = Item::all();
        return $this->successResponse($items, 'Items retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/items',
        summary: 'Create an item',
        tags: ['Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price', 'quantity', 'category_id', 'supplier_id', 'location_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 0),
                    new OA\Property(property: 'category_id', type: 'integer'),
                    new OA\Property(property: 'supplier_id', type: 'integer'),
                    new OA\Property(property: 'location_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Item created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/items/{item}',
        summary: 'Show an item',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'item', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item retrieved successfully'),
            new OA\Response(response: 404, description: 'Item not found'),
        ]
    )]
    public function show($item)
    {
        try {
            $data = Item::find($item);
            if (! $data) {
                return $this->notFoundResponse('Item not found');
            }

            return $this->successResponse($data, 'Item retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/items/{item}',
        summary: 'Update an item',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'item', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 0),
                    new OA\Property(property: 'category_id', type: 'integer'),
                    new OA\Property(property: 'supplier_id', type: 'integer'),
                    new OA\Property(property: 'location_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Item updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/items/{item}',
        summary: 'Delete an item',
        tags: ['Items'],
        parameters: [
            new OA\Parameter(name: 'item', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item deleted successfully'),
        ]
    )]
    public function destroy(Item $item)
    {
        $item->delete();
        return $this->successResponse(null, 'Item deleted successfully');
    }
}
