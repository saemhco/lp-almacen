<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();

        return $this->successResponse($suppliers, 'Suppliers retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($supplier)
    {
        try {
            $data = Supplier::find($supplier);
            if (! $data) {
                return $this->errorResponse('Supplier not found', null, 404);
            }

            return $this->successResponse($data, 'Supplier retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
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
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return $this->successResponse(null, 'Supplier deleted successfully');
    }
}
