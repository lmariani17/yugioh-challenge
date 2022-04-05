<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadRequestResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SubtypeResource;
use App\Repositories\SubtypeRepositoryInterface;
use App\Rules\AlphaSpace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @return SubtypeResource|BadRequestException|ErrorResource
     */
    public function store(Request $request): SubtypeResource|BadRequestException|ErrorResource
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', new AlphaSpace],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response =  new SubtypeResource($this->repository->create($request->all()));
        } catch (BadRequestException $badRequestException) {
            $response = new BadRequestResource($badRequestException);
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
     * @param Request $request
     * @param int $id
     * @return SubtypeResource|BadRequestException|ErrorResource
     */
    public function update(Request $request, int $id): SubtypeResource|BadRequestException|ErrorResource
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [new AlphaSpace],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = new SubtypeResource($this->repository->update($request->all(), $id));
        } catch (BadRequestException $badRequestException) {
            $response = new BadRequestResource($badRequestException);
        } catch (ModelNotFoundException $notFoundException) {
            $response = new ErrorResource($notFoundException);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }
}
