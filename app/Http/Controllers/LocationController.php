<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();

        return $this->successResponse($locations, 'Locations retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($location)
    {
        try {
            $data = Location::find($location);
            if (! $data) {
                return $this->errorResponse('Location not found', null, 404);
            }

            return $this->successResponse($data, 'Location retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
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
            return $this->errorResponse($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            return $this->errorResponse('Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return $this->successResponse(null, 'Location deleted successfully');
    }
}
