<?php

namespace App\Http\Controllers;

use App\Models\CustomerTransaction;
use App\Http\Requests\StoreCustomerTransactionRequest;
use App\Http\Requests\UpdateCustomerTransactionRequest;

class CustomerTransactionController extends Controller
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
    public function store(StoreCustomerTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerTransaction $customerTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerTransaction $customerTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerTransactionRequest $request, CustomerTransaction $customerTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerTransaction $customerTransaction)
    {
        //
    }
}
