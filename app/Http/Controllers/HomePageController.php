<?php

namespace App\Http\Controllers;

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

        return view('ryo-homepage', compact('newArrivalProducts'));
    }
}
