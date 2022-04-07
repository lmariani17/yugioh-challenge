<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Resources\AuthResource;
use App\Repositories\UserRepositoryInterface;
use App\Rules\AlphaSpace;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create API token.
     *
     * @OA\Post(
     *     path="/api/auth/tokens",
     *     tags={"Auth"},
     *     operationId="auth-store",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"user_id", "token_name"},
     *                  @OA\Property(type="integer", property="user_id", description="User ID"),
     *                  @OA\Property(type="string", property="token_name", description="Token"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Token created.",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResource")
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
                'user_id' => ['required', 'exists:users,id'],
                'token_name' => ['required', new AlphaSpace]
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }
            
            $user = $this->repository->findOrFail($request->user_id);
            $response = Response(new AuthResource($user->createToken($request->token_name)));
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException, $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
