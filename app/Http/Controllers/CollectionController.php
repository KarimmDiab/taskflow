<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;

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

        $countCollectionProducts = $collection->products()->count();
        $collectionProducts = $collection->products()->with(['images', 'category', 'subCategory', 'productVariants' => function ($query) {
            $query->with([
                'color',
                'size',
                'inventories',
            ])->where('is_active', true);
        },
        ])->get();

        return view('ryo-explore-collections', compact('collection', 'countCollectionProducts', 'collectionProducts'));
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
