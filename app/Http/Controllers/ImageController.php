<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\ImageResource;
use App\Repository\ImageRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageController extends Controller
{
    private ImageRepositoryInterface $repository;

    public function __construct(ImageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ImageResource::collection($this->repository->all());
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
            $response = new ImageResource($this->repository->create($request->toArray()));
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
            $response = new ImageResource($this->repository->findOrFail($id));
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
            $response = new ImageResource($this->repository->update($request->toArray(), $id));
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
     * @return ErrorResource|JsonResource
     */
    public function destroy(int $id)
    {
        try {
            $response = JsonResource::make($this->repository->delete($id));
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }
}
