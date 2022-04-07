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
     *         description="Get a cards collection",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CardResource"))
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
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  required={"name", "description", "first_edition", "serial_code", "type", "amount", "subtype_id", "image_id"},
     *                  @OA\Property(type="integer", property="id", description="Card's ID", format=""),
     *                  @OA\Property(type="string", property="name", description="Card's name", format=""),
     *                  @OA\Property(type="string", property="description", description="Card's description", format=""),
     *                  @OA\Property(type="boolean", property="first_edition", description="Is first edition", format=""),
     *                  @OA\Property(type="string", property="serial_code", description="Card's serial code", format=""),
     *                  @OA\Property(type="string", property="type", description="Card's type", format="", enum={"Monster", "Magic", "Trap"}),
     *                  @OA\Property(type="integer", property="attack", description="Attack of card", format=""),
     *                  @OA\Property(type="integer", property="defense", description="Defense of card", format=""),
     *                  @OA\Property(type="integer", property="star", description="Quality of card", format=""),
     *                  @OA\Property(type="number", property="amount", description="Amount of card", format=""),
     *                  @OA\Property(type="integer", property="subtype_id", description="Card's subtype", format=""),
     *                  @OA\Property(type="integer",property="image_id", description="Card's image", format="")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Card created.",
     *         @OA\JsonContent(ref="#/components/schemas/CardResource")
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
            $response = Response(new ErrorResource($badRequestException), $badRequestException->getCode());
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
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
     *         description="Card obteined.",
     *         @OA\JsonContent(ref="#/components/schemas/CardResource")
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
            $response = Response(new ErrorResource($notFoundException), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Update a specific card.
     *
     * @OA\Patch (
     *     path="/api/cards/{id}",
     *     tags={"Cards"},
     *     operationId="card-update",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Card ID.",
     *          required=true
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                  @OA\Property(type="integer", property="id", description="Card's ID", format=""),
     *                  @OA\Property(type="string", property="name", description="Card's name", format=""),
     *                  @OA\Property(type="string", property="description", description="Card's description", format=""),
     *                  @OA\Property(type="boolean", property="first_edition", description="Is first edition", format=""),
     *                  @OA\Property(type="string", property="serial_code", description="Card's serial code", format=""),
     *                  @OA\Property(type="string", property="type", description="Card's type", format="", enum={"Monster", "Magic", "Trap"}),
     *                  @OA\Property(type="integer", property="attack", description="Attack of card", format=""),
     *                  @OA\Property(type="integer", property="defense", description="Defense of card", format=""),
     *                  @OA\Property(type="integer", property="star", description="Quality of card", format=""),
     *                  @OA\Property(type="number", property="amount", description="Amount of card", format=""),
     *                  @OA\Property(type="integer", property="subtype_id", description="Card's subtype", format=""),
     *                  @OA\Property(type="integer",property="image_id", description="Card's image", format="")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card updated.",
     *         @OA\JsonContent(ref="#/components/schemas/CardResource")
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad request.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found.",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error.",
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
            $response = Response(new ErrorResource($badRequestException), $badRequestException->getCode());
        } catch (ModelNotFoundException $notFoundException) {
            $response = Response(new ErrorResource($notFoundException), $notFoundException->getCode());
        } catch (Exception $exception) {
            $response = Response(new ErrorResource($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
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
     *         description="Internal server error.",
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
