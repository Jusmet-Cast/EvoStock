<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index(): JsonResponse
    {
        $summary = $this->dashboard->summary();

        return response()->json([
            'data' => [
                'total_products' => $summary['total_products'],
                'total_categories' => $summary['total_categories'],
                'active_products' => $summary['active_products'],
                'inactive_products' => $summary['inactive_products'],
                'low_stock_products' => $summary['low_stock_products'],
                'latest_products' => ProductResource::collection($summary['latest_products']),
            ],
        ]);
    }
}
