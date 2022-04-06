<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadRequestResource;
use App\Http\Resources\CardResource;
use App\Http\Resources\ErrorResource;
use App\Repositories\CardRepositoryInterface;
use App\Rules\AlphaSpace;
use App\Rules\Decimal;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class CardController extends Controller
{
    private CardRepositoryInterface $repository;

    public function __construct(CardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all cards.
     *
     * @OA\Get(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     operationId="card-index",
     *     @OA\Response(
     *         response=200,
     *         description="Get a cards collection"
     *     )
     * )
     */
    public function index(): Response
    {
        return Response(CardResource::collection($this->repository->all()));
    }

    /**
     * Create a card.
     *
     * @OA\Post(
     *     path="/api/cards",
     *     tags={"Cards"},
     *     operationId="card-store",
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Card name.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="Card description.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="first_edition",
     *          in="query",
     *          description="Is first edition card.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="serial_code",
     *          in="query",
     *          description="Card's serial code.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          description="Type of card (Monster, Magic, Trap).",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="attack",
     *          in="query",
     *          description="Points of attack.",
     *      ),
     *     @OA\Parameter(
     *          name="defense",
     *          in="query",
     *          description="Points of defense.",
     *      ),
     *     @OA\Parameter(
     *          name="star",
     *          in="query",
     *          description="Card's quality.",
     *      ),
     *     @OA\Parameter(
     *          name="amount",
     *          in="query",
     *          description="Price of card.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="subtype_id",
     *          in="query",
     *          description="Subtype of card.",
     *          required=true,
     *      ),
     *     @OA\Parameter(
     *          name="image_id",
     *          in="query",
     *          description="Image of card.",
     *          required=true,
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Card created."
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
                'description' => ['required', new AlphaSpace],
                'first_edition' => ['required', 'boolean'],
                'serial_code' => ['required', 'alpha_num'],
                'type' => ['required', Rule::in(['Monster', 'Magic', 'Trap'])],
                'attack' => ['integer'],
                'defense' => ['integer'],
                'star' => ['integer'],
                'amount' => ['required', new Decimal],
                'subtype_id' => ['required', 'exists:subtypes,id'],
                'image_id' => ['required', 'exists:images,id']
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new CardResource($this->repository->create($request->all())));
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Get a specific card.
     *
     * @OA\Get(
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     operationId="card-show",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Card ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Card obteined."
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
            $response = Response(new CardResource($this->repository->findOrFail($id)));
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException->getMessage(), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Update a specific card.
     *
     * @OA\Patch (
     *     path="/api/cards",
     *     tags={"Cards"},
     *     operationId="card-update",
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Card's name.",
     *      ),
     *     @OA\Parameter(
     *          name="description",
     *          in="query",
     *          description="Card's description.",
     *      ),
     *     @OA\Parameter(
     *          name="first_edition",
     *          in="query",
     *          description="Is first edition card.",
     *      ),
     *     @OA\Parameter(
     *          name="serial_code",
     *          in="query",
     *          description="Card's serial code.",
     *      ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          description="Type of card (Monster, Magic, Trap).",
     *      ),
     *     @OA\Parameter(
     *          name="attack",
     *          in="query",
     *          description="Points of attack.",
     *      ),
     *     @OA\Parameter(
     *          name="defense",
     *          in="query",
     *          description="Points of defense.",
     *      ),
     *     @OA\Parameter(
     *          name="star",
     *          in="query",
     *          description="Card's quality.",
     *      ),
     *     @OA\Parameter(
     *          name="amount",
     *          in="query",
     *          description="Price of card.",
     *      ),
     *     @OA\Parameter(
     *          name="subtype_id",
     *          in="query",
     *          description="Subtype of card.",
     *      ),
     *     @OA\Parameter(
     *          name="image_id",
     *          in="query",
     *          description="Image of card.",
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Card created."
     *     ),
     *      @OA\Response(
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
                'description' => [new AlphaSpace],
                'first_edition' => ['boolean'],
                'serial_code' => ['alpha_num'],
                'type' => [Rule::in(['Monster', 'Magic', 'Trap'])],
                'attack' => ['integer'],
                'defense' => ['integer'],
                'star' => ['integer'],
                'amount' => [new Decimal],
                'subtype_id' => ['exists:subtypes,id'],
                'image_id' => ['exists:images,id']
            ]);

            if ($validator->fails()) {
                throw new BadRequestException($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $response = Response(new CardResource($this->repository->update($request->all(), $id)));
        } catch (BadRequestException $badRequestException) {
            $response = Response($badRequestException->getMessage(), $badRequestException->getCode());
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response($notFoundException->getMessage(), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Delete a card.
     *
     * @OA\Delete(
     *     path="/api/card/{id}",
     *     tags={"Cards"},
     *     operationId="card-delete",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Card ID.",
     *          required=true
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Card deleted."
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
            $response = Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
