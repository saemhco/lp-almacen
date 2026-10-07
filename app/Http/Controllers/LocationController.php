<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LocationController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/api/locations',
        summary: 'List locations',
        tags: ['Locations'],
        responses: [
            new OA\Response(response: 200, description: 'Locations retrieved successfully'),
        ]
    )]
    public function index()
    {
        $locations = Location::all();

        return $this->successResponse($locations, 'Locations retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/api/locations',
        summary: 'Create a location',
        tags: ['Locations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'code'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'code', type: 'string', maxLength: 255),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Location created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:locations,code',
            ]);

            $location = Location::create($validatedData);

            return $this->successResponse($location, 'Location created successfully', 201);
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
        path: '/api/locations/{location}',
        summary: 'Show a location',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(name: 'location', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Location retrieved successfully'),
            new OA\Response(response: 404, description: 'Location not found'),
        ]
    )]
    public function show($location)
    {
        try {
            $data = Location::find($location);
            if (! $data) {
                return $this->notFoundResponse('Location not found');
            }

            return $this->successResponse($data, 'Location retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/api/locations/{location}',
        summary: 'Update a location',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(name: 'location', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'code', type: 'string', maxLength: 255),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Location updated successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, Location $location)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:255|unique:locations,code,'.$location->id,
            ]);

            $location->update($validatedData);

            return $this->successResponse($location, 'Location updated successfully');
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
        path: '/api/locations/{location}',
        summary: 'Delete a location',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(name: 'location', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Location deleted successfully'),
        ]
    )]
    public function destroy(Location $location)
    {
        $location->delete();

        return $this->successResponse(null, 'Location deleted successfully');
    }
}
