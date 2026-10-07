<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = Movement::all();

        return $this->successResponse($movements, 'Movements retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
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
    public function destroy(Movement $movement)
    {
        $movement->delete();

        return $this->successResponse(null, 'Movement deleted successfully');
    }
}
