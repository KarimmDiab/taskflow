<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\ProductVariant;

// use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        $colorVariantIds = ProductVariant::query()
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('product_variants.is_active', true)
            ->where('products.is_active', true)
            ->whereNull('products.deleted_at')
            ->selectRaw('MIN(product_variants.id)')
            ->groupBy('product_variants.product_id', 'product_variants.color_id');

        $variantRelations = [
            'color',
            'size',
            'product.category',
            'product.subCategory',
            'product.images',
            'product.primaryImage',
            'product.collection',
        ];

        $newArrivalProducts = ProductVariant::with($variantRelations)
            ->whereIn('id', clone $colorVariantIds)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $bestSeller = ProductVariant::with($variantRelations)
            ->whereIn('id', clone $colorVariantIds)
            ->inRandomOrder()
            ->take(5)
            ->get();

        $collections = Collection::paginate(2);

        $featuredCollections = Collection::where('is_featured', true)
        ->paginate(1);

        $categories = Category::where('is_featured', true)
        ->where('is_active', true)
        ->paginate(5);

        return view('ryo-homepage', compact('newArrivalProducts', 'collections', 'bestSeller', 'featuredCollections', 'categories'));
    }
}
