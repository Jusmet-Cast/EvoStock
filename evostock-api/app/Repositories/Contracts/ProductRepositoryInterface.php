<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @param  array{search?: string, category_id?: int, status?: bool, sort_by?: string, sort_dir?: string}  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function countActive(): int;

    public function countInactive(): int;

    public function countLowStock(int $threshold = 10): int;

    /**
     * @return Collection<int, Product>
     */
    public function latest(int $limit = 5): Collection;
}
