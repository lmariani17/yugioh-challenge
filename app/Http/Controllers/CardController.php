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
     * @param Request $request
     * @return CardResource|BadRequestResource|ErrorResource
     */
    public function store(Request $request): CardResource|BadRequestResource|ErrorResource
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

            $response = new CardResource($this->repository->create($request->all()));
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
     * @return CardResource|ErrorResource
     */
    public function show(int $id): CardResource|ErrorResource
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
     * @param Request $request
     * @param int $id
     * @return CardResource|BadRequestResource|ErrorResource
     */
    public function update(Request $request, int $id): CardResource|BadRequestResource|ErrorResource
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

            $response = new CardResource($this->repository->update($request->all(), $id));
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
