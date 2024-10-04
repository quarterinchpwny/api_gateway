<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Traits\HttpResponses;
use Illuminate\Http\Response;
use Exception;

class ServiceController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $services = Service::all();
            return $this->successResponse($services, 'Services retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error retrieving services', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            $validated = $request->validated();
            $service = Service::create($validated);
            return $this->successResponse($service, 'Service created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error creating service', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        try {
            return $this->successResponse(Response::HTTP_OK, 'Service retrieved successfully', $service);
        } catch (Exception $e) {
            return $this->errorResponse(500, 'Error retrieving service', $e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $service = Service::findOrFail($id);
            $service->update($validated);
            return $this->successResponse(Response::HTTP_OK, 'Service updated successfully', $service);
        } catch (Exception $e) {
            return $this->errorResponse(500, 'Error updating service', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return $this->successResponse(Response::HTTP_NO_CONTENT, 'Service deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse(500, 'Error deleting service', $e);
        }
    }
}
