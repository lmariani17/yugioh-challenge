<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ImageResource;
use App\Repositories\ImageRepositoryInterface;
use App\Rules\AlphaSpace;
use App\Rules\ImageExtension;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
     * List all images.
     *
     * @OA\Get(
     *     path="/api/images",
     *     tags={"Images"},
     *     operationId="image-index",
     *     @OA\Response(
     *         response=200,
     *         description="Get an image collection",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ImageResource"))
     *     )
     * )
     */
    public function index(): Response
    {
        return Response(ImageResource::collection($this->repository->all()));
    }

    /**
     * Create an image.
     *
     * @OA\Post(
     *     path="/api/images",
     *     tags={"Images"},
     *     operationId="image-store",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"name", "extension", "file"},
     *                  @OA\Property(type="string", property="name", description="File name"),
     *                  @OA\Property(type="string", property="extension", description="File extension"),
     *                  @OA\Property(type="string", property="file", description="Base64 code")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Image created.",
     *         @OA\JsonContent(ref="#/components/schemas/ImageResource")
     *     ),
     *     @OA\Response(
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
                'extension' => ['required', new ImageExtension],
                'file' => ['required'],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new ImageResource($this->repository->create($request->all())), Response::HTTP_CREATED);
        } catch (BadRequestException $badRequestException) {
            $response = Response(new ErrorResource($badRequestException), $badRequestException->getCode());
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Get a specific image.
     *
     * @OA\Get(
     *     path="/api/images/{id}",
     *     tags={"Images"},
     *     operationId="image-show",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Image ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Image obteined.",
     *         @OA\JsonContent(ref="#/components/schemas/ImageResource")
     *     ),
     *     @OA\Response(
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
            $response = Response(new ImageResource($this->repository->findOrFail($id)));
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException, $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Create an image.
     *
     * @OA\Patch(
     *     path="/api/images/{id}",
     *     tags={"Images"},
     *     operationId="image-update",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  @OA\Property(type="string", property="name", description="File name"),
     *                  @OA\Property(type="string", property="extension", description="File extension"),
     *                  @OA\Property(type="string", property="file", description="Base64 code")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image updated.",
     *         @OA\JsonContent(ref="#/components/schemas/ImageResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request."
     *     ),
     *     @OA\Response(
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
                'extension' => [new ImageExtension],
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new ImageResource($this->repository->update($request->all(), $id)));
        } catch (BadRequestException $badRequestException) {
            $response = Response(new ErrorResource($badRequestException), $badRequestException->getCode());
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response(new ErrorResource($notFoundException), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Delete an image.
     *
     * @OA\Delete(
     *     path="/api/images/{id}",
     *     tags={"Images"},
     *     operationId="image-delete",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Image ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Image deleted."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    public function destroy(int $id): Response
    {
        try {
            $response = Response($this->repository->delete($id));
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
