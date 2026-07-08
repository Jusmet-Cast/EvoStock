<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    private const SORTABLE_COLUMNS = ['name', 'entry_date'];

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $sortBy = in_array($filters['sort_by'] ?? null, self::SORTABLE_COLUMNS, true)
            ? $filters['sort_by']
            : 'entry_date';

        $sortDir = ($filters['sort_dir'] ?? null) === 'asc' ? 'asc' : 'desc';

        return Product::query()
            ->with('categories')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->whereHas(
                'categories',
                fn ($categoryQuery) => $categoryQuery->whereKey($categoryId)
            ))
            ->when(array_key_exists('status', $filters) && $filters['status'] !== null, fn ($query) => $query->where('is_active', $filters['status']))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function countActive(): int
    {
        return Product::query()->where('is_active', true)->count();
    }

    public function countInactive(): int
    {
        return Product::query()->where('is_active', false)->count();
    }

    public function countLowStock(int $threshold = 10): int
    {
        return Product::query()->where('stock', '<', $threshold)->count();
    }

    public function latest(int $limit = 5): Collection
    {
        return Product::query()
            ->orderByDesc('entry_date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
