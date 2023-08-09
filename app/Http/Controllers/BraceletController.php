<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBraceletRequest;
use App\Http\Requests\UpdateBraceletRequest;
use App\Models\Bracelet;

class BraceletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        return view('bracelets.dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bracelets.index');
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
    public function store(StoreBraceletRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bracelet $bracelet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bracelet $bracelet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBraceletRequest $request, Bracelet $bracelet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bracelet $bracelet)
    {
        //
    }
}
