<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;

// use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        $newArrivalProducts = Product::with(
            'category', 'subCategory', 'images',
            'primaryImage', 'productVariants.color',
            'collection', 'productVariants.size')
            ->where('is_active', true)
            ->latest()
            ->paginate(4);

        $bestSeller = Product::with(
            'category', 'subCategory', 'images',
            'primaryImage', 'productVariants.color',
            'collection', 'productVariants.size')
            ->where('is_active', true)
            ->inRandomOrder()
            ->paginate(5);

        $collections = Collection::paginate(2);

        $featuredCollections = Collection::where('is_featured', true)
        ->paginate(1);

        $categories = Category::where('is_featured', true)
        ->paginate(4);

        return view('ryo-homepage', compact('newArrivalProducts', 'collections', 'bestSeller', 'featuredCollections', 'categories'));
    }
}
