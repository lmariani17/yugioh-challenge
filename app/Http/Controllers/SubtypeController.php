<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtypeResource;
use App\Repositories\SubtypeRepositoryInterface;
use App\Rules\AlphaSpace;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Request;
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
     * List all subtypes.
     *
     * @OA\Get(
     *     path="/api/subtypes",
     *     tags={"Subtypes"},
     *     operationId="subtype-index",
     *     @OA\Response(
     *         response=200,
     *         description="Get a subtypes collection"
     *     )
     * )
     */
    public function index(): Response
    {
        return Response(SubtypeResource::collection($this->repository->all()));
    }

    /**
     * Create a subtype.
     *
     * @OA\Post(
     *     path="/api/subtypes",
     *     tags={"Subtypes"},
     *     operationId="subtype-store",
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Subtype name.",
     *          required=true,
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Subtype created."
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad request."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    public function store(Request $request): Response
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', new AlphaSpace],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new SubtypeResource($this->repository->create($request->all())), Response::HTTP_CREATED);
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Get a specific subtype.
     *
     * @OA\Get(
     *     path="/api/subtypes/{id}",
     *     tags={"Subtypes"},
     *     operationId="subtype-show",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Subtype ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Subtype obteined."
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    public function show(int $id): Response
    {
        try {
            $response =  Response(new SubtypeResource($this->repository->findOrFail($id)));
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException->getMessage(), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Update a subtype.
     *
     * @OA\Put(
     *     path="/api/subtypes/{id}",
     *     tags={"Subtypes"},
     *     operationId="subtype-update",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Subtype ID.",
     *          required=true
     *      ),
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="New subtype name."
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Subtype updated."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request."
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="Not found."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [new AlphaSpace],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new SubtypeResource($this->repository->update($request->all(), $id)));
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException->getMessage(), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
