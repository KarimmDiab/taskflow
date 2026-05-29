<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Collection;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $completeLookProducts = Product::with([
            'images',
            'category',
            'subCategory',
            'productVariants' => function ($query) {

                $query->with([
                    'color',
                    'size',
                    'inventories',
                ])->where('is_active', true);
            },
        ])
            ->inRandomOrder()
            ->take(2)
            ->get();

        return view('ryo-cart', compact('completeLookProducts'));

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
        $productImage= $product->load('images', 'productVariants.color', 'productVariants.size');

        return view('ryo-product', compact('selectedProdcut', 'productImage'));
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

        return view('ryo-collections', compact('all_collections', 'featured_collections'));
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
