<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Http\Resources\ErrorResource;
use App\Models\Card;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CardResource::collection(Card::all());
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
            $cardCreated = Card::create($request->toArray());
            $response = new CardResource($cardCreated);
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
            $response = new CardResource(Card::findOrFail($id));
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
            Card::findOrFail($id)->update($request->toArray());
            $cardUpdated = Card::find($id);

            $response = new CardResource($cardUpdated);
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
     * @param Card $card
     * @return Response
     */
    public function destroy(Card $card)
    {
        //
    }
}
