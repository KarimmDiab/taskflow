<?php

namespace App\Http\Controllers;

use App\Models\ExpensesItem;
use App\Http\Requests\StoreExpensesItemRequest;
use App\Http\Requests\UpdateExpensesItemRequest;

class ExpensesItemController extends Controller
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
    public function store(StoreExpensesItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpensesItem $expensesItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpensesItem $expensesItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpensesItemRequest $request, ExpensesItem $expensesItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpensesItem $expensesItem)
    {
        //
    }
}
