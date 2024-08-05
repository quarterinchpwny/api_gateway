<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    public function forwardRequest(Request $request, $service, $endpoint)
    {
        try {
            // Retrieve service URL dynamically based on the service name
            $service = Service::where('name', $service)->first();

            if (!$service) {
                return $this->errorResponse(404, 'Service not found');
            }

            $url = $service->service_ip . '/api/' . $endpoint;
            $method = $request->method();

            $bearerToken = $request->bearerToken();

            if (!isset($bearerToken) || !Auth::guard('sanctum')->check()) {
                return $this->errorResponse(401, 'Unauthenticated');
            }

            $formData = $request->request->all();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
                'Accept' => 'application/json',
            ])->send($method, $url, [
                'query' => $request->query(),
                'json' => $formData,

            ]);

            return $this->successResponse(200, 'Request forwarded successfully', json_decode($response->body(), true));
        } catch (Exception $e) {
            return $this->errorResponse(500, 'Internal server error', $e);
        }
    }
}
