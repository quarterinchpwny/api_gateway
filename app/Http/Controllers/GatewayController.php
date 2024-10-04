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
                return $this->errorResponse(new Exception('Service not found'), null, 404);
            }

            $url = "{$service->service_ip}/api/{$endpoint}";
            $method = $request->method();

            $bearerToken = $request->bearerToken();

            // if (!isset($bearerToken) || !Auth::guard('sanctum')->check()) {
            //     return $this->errorResponse(new Exception('Unauthenticated'), null, 401);
            // }

            $formData = $request->request->all();
            $apiKey = env('API_KEY');


            $response = Http::withHeaders([
                'Authorization' => "Bearer $bearerToken",
                'Accept' => 'application/json',
                'api_key' => $apiKey
            ])->send($method, $url, [
                'query' => $request->query(),
                'json' => $formData,

            ]);

            return $this->successResponse(json_decode($response->body(), true), 'Request forwarded successfully', 200);
        } catch (Exception $e) {

            return $this->errorResponse($e, 'Internal server error', 500);
        }
    }
}
