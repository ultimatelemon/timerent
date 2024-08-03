<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\StoreProduct;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends ApiController implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_PRODUCTS', only: ['index', 'show']),
            new Middleware('hasPermissions:VIEW_INVOICES', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function index(Request $request, Venue $venue): JsonResponse
    {
        $products = $venue->products();

        if($request->has('q'))
            $products = $products->where('name', 'ILIKE', "%{$request->q}%");

        $products = $products->paginate(env('POSTS_PER_PAGE'));

        return $this->success(
            ProductResource::collection($products),
            collect($products)->only(['from', 'to', 'total', 'per_page', 'last_page', 'current_page'])->toArray(),
        );
    }

    /**
     * Store a new resource
     *
     * @param StoreProduct $request
     * @param Venue $venue
     * @return JsonResponse
     */
    public function store(StoreProduct $request, Venue $venue): JsonResponse
    {
        $products = $venue->products()->create($request->validated());
        return $this->success(['message' => 'Product created successfully.']);
    }

    /**
     * Show the specific resource
     *
     * @param Venue $venue
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Venue $venue, Product $product): JsonResponse
    {
        return $this->success(new ProductResource($product));
    }

    /**
     * Update the specific product
     *
     * @param StoreProduct $request
     * @param Venue $venue
     * @param Product $product
     * @return JsonResponse
     */
    public function update(StoreProduct $request, Venue $venue, Product $product): JsonResponse
    {
        $product->update($request->except('units'));
        $product->units()->sync($request->units);
        return $this->success(['message' => 'Product updated successfully.']);
    }
}
