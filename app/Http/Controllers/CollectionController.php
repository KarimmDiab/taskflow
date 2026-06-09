<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\ProductVariant;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCollectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $collection = Collection::where('slug', $slug)
            ->with('products')
            ->firstOrFail();

        $colorVariantIds = ProductVariant::query()
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('products.collection_id', $collection->id)
            ->where('product_variants.is_active', true)
            ->where('products.is_active', true)
            ->whereNull('products.deleted_at')
            ->selectRaw('MIN(product_variants.id)')
            ->groupBy('product_variants.product_id', 'product_variants.color_id');

        $collectionProducts = ProductVariant::with([
            'color',
            'size',
            'inventories',
            'product.images',
            'product.category',
            'product.subCategory',
        ])
            ->whereIn('id', $colorVariantIds)
            ->latest()
            ->get();

        $countCollectionProducts = $collectionProducts->count();

        $categories = Category::where('is_featured', true)
            ->where('is_active', true)
            ->paginate(5);

        return view('ryo-explore-collections', compact('collection', 'countCollectionProducts', 'collectionProducts', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
