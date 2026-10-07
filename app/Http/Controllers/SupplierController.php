<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/suppliers',
        summary: 'List suppliers',
        tags: ['Suppliers'],
        responses: [
            new OA\Response(response: 200, description: 'Suppliers retrieved successfully'),
        ]
    )]
    public function index()
    {
        $suppliers = Supplier::all();

        return $this->successResponse($suppliers, 'Suppliers retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/suppliers',
        summary: 'Create a supplier',
        tags: ['Suppliers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Supplier created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
            ]);

            $supplier = Supplier::create($validatedData);

            return $this->successResponse($supplier, 'Supplier created successfully', 201);
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
        path: '/api/suppliers/{supplier}',
        summary: 'Show a supplier',
        tags: ['Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Supplier retrieved successfully'),
            new OA\Response(response: 404, description: 'Supplier not found'),
        ]
    )]
    public function show($supplier)
    {
        try {
            $data = Supplier::find($supplier);
            if (! $data) {
                return $this->notFoundResponse('Supplier not found');
            }

            return $this->successResponse($data, 'Supplier retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/suppliers/{supplier}',
        summary: 'Update a supplier',
        tags: ['Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'phone', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Supplier updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:255',
            ]);

            $supplier->update($validatedData);

            return $this->successResponse($supplier, 'Supplier updated successfully');
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
        path: '/api/suppliers/{supplier}',
        summary: 'Delete a supplier',
        tags: ['Suppliers'],
        parameters: [
            new OA\Parameter(name: 'supplier', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Supplier deleted successfully'),
        ]
    )]
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return $this->successResponse(null, 'Supplier deleted successfully');
    }
}
