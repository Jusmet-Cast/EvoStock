<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categories,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categories->list(
            filters: $request->only('search'),
            perPage: (int) $request->integer('per_page', 15),
        );

        return CategoryResource::collection($categories)->response();
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categories->create($request->validated());

        return CategoryResource::make($category)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Category $category): JsonResponse
    {
        return CategoryResource::make($category)->response();
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category = $this->categories->update($category, $request->validated());

        return CategoryResource::make($category)->response();
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categories->delete($category);

        return response()->json(null, 204);
    }
}
