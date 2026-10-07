<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MovementController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/movements',
        summary: 'List movements',
        tags: ['Movements'],
        responses: [
            new OA\Response(response: 200, description: 'Movements retrieved successfully'),
        ]
    )]
    public function index()
    {
        $movements = Movement::all();

        return $this->successResponse($movements, 'Movements retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/movements',
        summary: 'Create a movement',
        tags: ['Movements'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['item_id', 'user_id', 'type', 'quantity'],
                properties: [
                    new OA\Property(property: 'item_id', type: 'integer'),
                    new OA\Property(property: 'user_id', type: 'integer'),
                    new OA\Property(property: 'type', type: 'string', enum: ['entrada', 'salida']),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 1),
                    new OA\Property(property: 'note', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Movement created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_id' => 'required|exists:items,id',
                'user_id' => 'required|exists:users,id',
                'type' => 'required|in:'.Movement::ENTRADA.','.Movement::SALIDA,
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string',
            ]);

            $movement = Movement::create($validatedData);

            return $this->successResponse($movement, 'Movement created successfully', 201);
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
        path: '/api/movements/{movement}',
        summary: 'Show a movement',
        tags: ['Movements'],
        parameters: [
            new OA\Parameter(name: 'movement', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Movement retrieved successfully'),
            new OA\Response(response: 404, description: 'Movement not found'),
        ]
    )]
    public function show($movement)
    {
        try {
            $data = Movement::find($movement);
            if (! $data) {
                return $this->notFoundResponse('Movement not found');
            }

            return $this->successResponse($data, 'Movement retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/movements/{movement}',
        summary: 'Update a movement',
        tags: ['Movements'],
        parameters: [
            new OA\Parameter(name: 'movement', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'item_id', type: 'integer'),
                    new OA\Property(property: 'user_id', type: 'integer'),
                    new OA\Property(property: 'type', type: 'string', enum: ['entrada', 'salida']),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 1),
                    new OA\Property(property: 'note', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Movement updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, Movement $movement)
    {
        try {
            $validatedData = $request->validate([
                'item_id' => 'sometimes|required|exists:items,id',
                'user_id' => 'sometimes|required|exists:users,id',
                'type' => 'sometimes|required|in:'.Movement::ENTRADA.','.Movement::SALIDA,
                'quantity' => 'sometimes|required|integer|min:1',
                'note' => 'nullable|string',
            ]);

            $movement->update($validatedData);

            return $this->successResponse($movement, 'Movement updated successfully');
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
        path: '/api/movements/{movement}',
        summary: 'Delete a movement',
        tags: ['Movements'],
        parameters: [
            new OA\Parameter(name: 'movement', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Movement deleted successfully'),
        ]
    )]
    public function destroy(Movement $movement)
    {
        $movement->delete();

        return $this->successResponse(null, 'Movement deleted successfully');
    }
}
