<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadRequestResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\ImageResource;
use App\Repositories\ImageRepositoryInterface;
use App\Rules\AlphaSpace;
use App\Rules\ImageExtension;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @return ImageResource|BadRequestResource|ErrorResource
     */
    public function store(Request $request): BadRequestResource|ErrorResource|ImageResource
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', new AlphaSpace],
                'extension' => ['required', new ImageExtension],
                'file' => ['required'],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = new ImageResource($this->repository->create($request->all()));
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
     * @return ImageResource|ErrorResource
     */
    public function show(int $id): ImageResource|ErrorResource
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
     * @param Request $request
     * @param int $id
     * @return ImageResource|BadRequestResource|ErrorResource
     */
    public function update(Request $request, int $id): ImageResource|BadRequestResource|ErrorResource
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [new AlphaSpace],
                'extension' => [new ImageExtension],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = new ImageResource($this->repository->update($request->all(), $id));
        } catch (BadRequestException $badRequestException) {
            $response = new BadRequestResource($badRequestException);
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
     * @return int|ErrorResource
     */
    public function destroy(int $id): int|ErrorResource
    {
        try {
            $response = $this->repository->delete($id);
        } catch (Exception $exception) {
            $response = new ErrorResource($exception);
        }

        return $response;
    }
}
