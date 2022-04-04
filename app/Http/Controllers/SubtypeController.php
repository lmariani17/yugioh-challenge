<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtypeRequest;
use App\Http\Requests\UpdateSubtypeRequest;
use App\Models\Subtype;
use App\Http\Resources\SubtypeResource;

class SubtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubtypeResource::collection(Subtype::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubtypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubtypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subtype  $subtype
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return new SubtypeResource(Subtype::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubtypeRequest  $request
     * @param  \App\Models\Subtype  $subtype
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubtypeRequest $request, Subtype $subtype)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subtype  $subtype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subtype $subtype)
    {
        //
    }
}
