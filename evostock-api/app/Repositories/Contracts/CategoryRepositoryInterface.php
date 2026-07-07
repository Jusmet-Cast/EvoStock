<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * @param  array{search?: string}  $filters
     */
    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function nameExists(string $name, ?int $ignoreId = null): bool;
}
