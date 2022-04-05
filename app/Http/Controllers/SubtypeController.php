<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SubtypeResource;
use App\Http\Requests\StoreSubtypeRequest;
use App\Http\Requests\UpdateSubtypeRequest;
use App\Repositories\SubtypeRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubtypeController extends Controller
{
    private SubtypeRepositoryInterface $repository;

    public function __construct(SubtypeRepositoryInterface $repository)
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
        return SubtypeResource::collection($this->repository->all());
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
            $response =  new SubtypeResource($this->repository->create($request->toArray()));
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
            $response =  new SubtypeResource($this->repository->findOrFail($id));
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
            $response = new SubtypeResource($this->repository->update($request->toArray(), $id));
        } catch (ModelNotFoundException $notFoundException) {
            $response = new ErrorResource($notFoundException);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }
}
