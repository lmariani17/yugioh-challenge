<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SubtypeResource;
use App\Http\Requests\StoreSubtypeRequest;
use App\Http\Requests\UpdateSubtypeRequest;
use App\Models\Subtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class SubtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return SubtypeResource::collection(Subtype::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubtypeRequest $request
     * @return SubtypeResource
     */
    public function store(StoreSubtypeRequest $request): ErrorResource|SubtypeResource
    {
        try {
            $request->validate($request->rules());
            $subtypeCreated = Subtype::create($request->toArray());
            $response =  new SubtypeResource($subtypeCreated);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ErrorResource|SubtypeResource
     */
    public function show(int $id): ErrorResource|SubtypeResource
    {
        try {
            $response =  new SubtypeResource(Subtype::findOrFail($id));
        } catch (ModelNotFoundException $notFoundException) {
            $response = new ErrorResource($notFoundException);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSubtypeRequest $request
     * @param int $id
     * @return ErrorResource|SubtypeResource
     */
    public function update(UpdateSubtypeRequest $request, int $id): ErrorResource|SubtypeResource
    {
        try {
            $request->validate($request->rules());
            Subtype::findOrFail($id)->update($request->toArray());
            $response =  new SubtypeResource(Subtype::find($id));
        } catch (ModelNotFoundException $notFoundException) {
            $response = new ErrorResource($notFoundException);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
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
