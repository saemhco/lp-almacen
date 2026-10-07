<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return $this->successResponse($categories, 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ]);

            $category = Category::create($validatedData);

            return $this->successResponse($category, 'Category created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($category)
    {
        try {
            $data = Category::find($category);
            if (! $data) {
                return $this->errorResponse('Category not found', null, 404);
            }

            return $this->successResponse($data, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:categories,name,'.$category->id,
                'description' => 'nullable|string',
            ]);

            $category->update($validatedData);

            return $this->successResponse($category, 'Category updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully');
    }
}
