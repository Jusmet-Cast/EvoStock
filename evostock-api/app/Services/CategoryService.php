<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
    ) {}

    /**
     * @param  array{search?: string}  $filters
     */
    public function list(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->categories->paginate($filters, $perPage);
    }

    /**
     * @param  array{name: string, description?: ?string, is_active?: bool}  $data
     */
    public function create(array $data): Category
    {
        $this->guardAgainstDuplicateName($data['name']);

        return Category::create($data);
    }

    /**
     * @param  array{name?: string, description?: ?string, is_active?: bool}  $data
     */
    public function update(Category $category, array $data): Category
    {
        if (isset($data['name'])) {
            $this->guardAgainstDuplicateName($data['name'], $category->id);
        }

        $category->update($data);

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    private function guardAgainstDuplicateName(string $name, ?int $ignoreId = null): void
    {
        if ($this->categories->nameExists($name, $ignoreId)) {
            throw ValidationException::withMessages([
                'name' => 'Ya existe una categoría con este nombre.',
            ]);
        }
    }
}
