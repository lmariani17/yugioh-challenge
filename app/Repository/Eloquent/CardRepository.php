<?php

namespace App\Repository\Eloquent;

use App\Models\Card;
use App\Repository\CardRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CardRepository implements CardRepositoryInterface
{
    /**
     * @var Card
     */
    protected Card $model;

    /**
     * CardRepository constructor.
     *
     * @param Card $model
     */
    public function __construct(Card $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param array $attributes
     *
     * @return Card
     */
    public function create(array $attributes): Card
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Card|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Card
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Card not found", 404);
        }

        return $model;
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return Card
     * @throws ModelNotFoundException
     */
    public function update(array $attributes, int $id): Card
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Card not found", 404);
        }

        $model->update($attributes);

        return $model;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }
}
