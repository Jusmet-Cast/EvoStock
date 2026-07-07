<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    private const LOW_STOCK_THRESHOLD = 10;

    private const LATEST_PRODUCTS_LIMIT = 5;

    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    /**
     * @return array{
     *     total_products: int,
     *     total_categories: int,
     *     active_products: int,
     *     inactive_products: int,
     *     low_stock_products: int,
     *     latest_products: Collection<int, Product>,
     * }
     */
    public function summary(): array
    {
        return [
            'total_products' => Product::query()->count(),
            'total_categories' => Category::query()->count(),
            'active_products' => $this->products->countActive(),
            'inactive_products' => $this->products->countInactive(),
            'low_stock_products' => $this->products->countLowStock(self::LOW_STOCK_THRESHOLD),
            'latest_products' => $this->products->latest(self::LATEST_PRODUCTS_LIMIT),
        ];
    }
}
