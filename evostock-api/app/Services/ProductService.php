<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    /**
     * @param  array{search?: string, category_id?: int, status?: bool, sort_by?: string, sort_dir?: string}  $filters
     */
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->products->paginate($filters, $perPage);
    }

    /**
     * @param  array{name: string, description?: ?string, price: float, stock: int, entry_date: string, is_active?: bool, category_ids?: int[]}  $data
     */
    public function create(array $data): Product
    {
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        $product = Product::create($data);
        $product->categories()->sync($categoryIds);

        return $product->load('categories');
    }

    /**
     * @param  array{name?: string, description?: ?string, price?: float, stock?: int, entry_date?: string, is_active?: bool, category_ids?: int[]}  $data
     */
    public function update(Product $product, array $data): Product
    {
        $categoryIds = $data['category_ids'] ?? null;
        unset($data['category_ids']);

        $product->update($data);

        if ($categoryIds !== null) {
            $product->categories()->sync($categoryIds);
        }

        return $product->load('categories');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
