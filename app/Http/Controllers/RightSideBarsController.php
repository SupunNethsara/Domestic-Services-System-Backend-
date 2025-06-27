<?php

namespace App\Http\Controllers;

use App\Models\WorkersAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RightSideBarsController extends Controller
{
    public function getTopRatedServices()
    {
        try {
            $availabilities = WorkersAvailability::whereNotNull('services')->get();
            $allServices = collect();

            foreach ($availabilities as $availability) {
                $services = is_array($availability->services)
                    ? $availability->services
                    : json_decode($availability->services, true);

                foreach ($services as $service) {
                    if (!isset($service['name'], $service['rate'])) {
                        continue;
                    }
                    $allServices->push([
                        'name' => $service['name'],
                        'rate' => (float) $service['rate'],
                        'rate_type' => $service['rate_type'] ?? 'hourly',
                        'currency' => $service['currency'] ?? 'LKR'
                    ]);
                }
            }
            $topServices = $allServices
                ->sortByDesc('rate')
                ->unique('name')
                ->take(3)
                ->values();

            return response()->json([
                'success' => true,
                'top_rated_services' => $topServices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing services data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
