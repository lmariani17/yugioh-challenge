<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;
use App\Rules\AlphaSpace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all users.
     *
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     operationId="user-index",
     *     @OA\Response(
     *         response=200,
     *         description="Get an user collection",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *     )
     * )
     */
    public function index()
    {
        return Response(UserResource::collection($this->repository->all()));
    }

    /**
     * Create an user.
     *
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     operationId="user-store",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"name", "email", "password", "remember_token"},
     *                  @OA\Property(type="string", property="name", description="User name"),
     *                  @OA\Property(type="string", property="email", description="User's email"),
     *                  @OA\Property(type="string", property="password", description="User's password"),
     *                  @OA\Property(type="string", property="remember_token", description="Security token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', new AlphaSpace],
                'email' => ['required', 'email'],
                'password' => ['required', 'alpha_dash'],
                'remember_token' => ['required', new AlphaSpace]
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new UserResource($this->repository->create($request->all())), Response::HTTP_CREATED);
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Get a specific user.
     *
     * @OA\Get(
     *     path="/api/user/{id}",
     *     tags={"Users"},
     *     operationId="user-show",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="User obteined.",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
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
            $response = Response(new UserResource($this->repository->findOrFail($id)));
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException, $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
