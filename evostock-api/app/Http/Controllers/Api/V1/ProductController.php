<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $products,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->string('search')->toString() ?: null,
            'category_id' => $request->integer('category_id') ?: null,
            'status' => $request->has('status') ? $request->boolean('status') : null,
            'sort_by' => $request->string('sort_by')->toString() ?: null,
            'sort_dir' => $request->string('sort_dir')->toString() ?: null,
        ];

        $products = $this->products->list(
            filters: $filters,
            perPage: (int) $request->integer('per_page', 15),
        );

        return ProductResource::collection($products)->response();
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->products->create($request->validated());

        return ProductResource::make($product)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Product $product): JsonResponse
    {
        return ProductResource::make($product->load('categories'))->response();
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->products->update($product, $request->validated());

        return ProductResource::make($product)->response();
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->products->delete($product);

        return response()->json(null, 204);
    }
}
