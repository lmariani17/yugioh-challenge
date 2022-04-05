<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ErrorResource;
use App\Models\Image;
use App\Http\Resources\ImageResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ImageResource::collection(Image::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreImageRequest $request
     * @return ErrorResource|ImageResource
     */
    public function store(StoreImageRequest $request): ErrorResource|ImageResource
    {
        try {
            $request->validate($request->rules());
            $imageCreated = Image::create($request->toArray());
            $response = new ImageResource($imageCreated);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ErrorResource|ImageResource
     */
    public function show(int $id): ErrorResource|ImageResource
    {
        try {
            $response = new ImageResource(Image::findOrFail($id));
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
     * @param UpdateImageRequest $request
     * @param int $id
     * @return ErrorResource|ImageResource
     */
    public function update(UpdateImageRequest $request, int $id): ErrorResource|ImageResource
    {
        try {
            $request->validate($request->rules());
            Image::findOrFail($id)->update($request->toArray());
            $imageUpdated = Image::find($id);
            $response = new ImageResource($imageUpdated);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): \Illuminate\Http\Response
    {
        //
    }
}
