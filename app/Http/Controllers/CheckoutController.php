<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Requests\UpdateCheckoutRequest;
use App\Models\Checkout;
use App\Models\Shipping;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $govs = Shipping::where('is_active', true)->get(); // فقط المحافظات النشطة
        return view('ryo-checkout', compact('govs'));
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
    public function store(StoreCheckoutRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkout $Checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $Checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckoutRequest $request, Checkout $Checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $Checkout)
    {
        //
    }
}
