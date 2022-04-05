<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Http\Resources\ErrorResource;
use App\Repositories\CardRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CardController extends Controller
{
    private CardRepositoryInterface $repository;

    public function __construct(CardRepositoryInterface $repository)
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
        return CardResource::collection($this->repository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCardRequest $request
     * @return ErrorResource|CardResource
     */
    public function store(StoreCardRequest $request): ErrorResource|CardResource
    {
        try {
            $request->validate($request->rules());
            $response = new CardResource($this->repository->create($request->toArray()));
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ErrorResource|CardResource
     */
    public function show(int $id): ErrorResource|CardResource
    {
        try {
            $response = new CardResource($this->repository->findOrFail($id));
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
     * @param UpdateCardRequest $request
     * @param int $id
     * @return ErrorResource|CardResource
     */
    public function update(UpdateCardRequest $request, int $id): ErrorResource|CardResource
    {
        try {
            $request->validate($request->rules());
            $response = new CardResource($this->repository->update($request->toArray(), $id));
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
     * @return ErrorResource|int
     */
    public function destroy(int $id): ErrorResource|int
    {
        try {
            $response = $this->repository->delete($id);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }
}
