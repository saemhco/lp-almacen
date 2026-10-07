<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/categories',
        summary: 'List categories',
        tags: ['Categories'],
        responses: [
            new OA\Response(response: 200, description: 'Categories retrieved successfully'),
        ]
    )]
    public function index()
    {
        $categories = Category::all();

        return $this->successResponse($categories, 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/categories',
        summary: 'Create a category',
        tags: ['Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Category created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/api/categories/{category}',
        summary: 'Show a category',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Category retrieved successfully'),
            new OA\Response(response: 404, description: 'Category not found'),
        ]
    )]
    public function show($category)
    {
        try {
            $data = Category::find($category);
            if (! $data) {
                return $this->notFoundResponse('Category not found');
            }

            return $this->successResponse($data, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/categories/{category}',
        summary: 'Update a category',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Category updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/api/categories/{category}',
        summary: 'Delete a category',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Category deleted successfully'),
        ]
    )]
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->successResponse(null, 'Category deleted successfully');
    }
}
