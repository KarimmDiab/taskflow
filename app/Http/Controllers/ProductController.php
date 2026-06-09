<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $completeLookProducts = ProductVariant::with([
            'color',
            'size',
            'inventories',
            'product.images',
            'product.primaryImage',
        ])
            ->where('is_active', true)
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->inRandomOrder()
            ->take(2)
            ->get();

        $categories = Category::where('is_featured', true)
            ->where('is_active', true)
            ->paginate(5);

        return view('ryo-cart', compact('completeLookProducts', 'categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $selectedProdcut = Product::with(['images', 'category', 'subCategory', 'productVariants' => function ($query) {
            $query->with([
                'color',
                'size',
                'inventories',
            ])->where('is_active', true);
        },
        ])->where('slug', $product->slug)
            ->firstOrFail();
        $productImage = $product->load('images', 'productVariants.color', 'productVariants.size');
        $categories = Category::where('is_featured', true)
            ->where('is_active', true)
            ->paginate(5);

        return view('ryo-product', compact('selectedProdcut', 'productImage', 'categories'));
    }

    public function showAllCollection()
    {
        $all_collections = Collection::with('products')
            ->withCount('products')
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();
        $featured_collections = Collection::withCount('products')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->first();

        $collectionIds = $all_collections->pluck('id');
        if ($featured_collections) {
            $collectionIds->push($featured_collections->id);
        }

        $variantCountsByCollection = ProductVariant::query()
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->whereIn('products.collection_id', $collectionIds->unique()->filter())
            ->where('product_variants.is_active', true)
            ->where('products.is_active', true)
            ->whereNull('products.deleted_at')
            ->selectRaw("products.collection_id, COUNT(DISTINCT CONCAT(product_variants.product_id, ':', COALESCE(product_variants.color_id, 0))) as aggregate")
            ->groupBy('products.collection_id')
            ->pluck('aggregate', 'products.collection_id');

        $all_collections->each(function ($collection) use ($variantCountsByCollection) {
            $collection->setAttribute('products_count', (int) ($variantCountsByCollection[$collection->id] ?? 0));
        });

        if ($featured_collections) {
            $featured_collections->setAttribute(
                'products_count',
                (int) ($variantCountsByCollection[$featured_collections->id] ?? 0)
            );
        }

        $categories = Category::where('is_featured', true)
            ->where('is_active', true)
            ->paginate(5);



        return view('ryo-collections', compact('all_collections', 'featured_collections', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
