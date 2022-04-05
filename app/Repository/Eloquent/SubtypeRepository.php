<?php

namespace App\Repository\Eloquent;

use App\Models\Subtype;
use App\Repository\SubtypeRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class SubtypeRepository implements SubtypeRepositoryInterface
{
    /**
     * @var Subtype
     */
    protected Subtype $model;

    /**
     * SubtypeRepository constructor.
     *
     * @param Subtype $model
     */
    public function __construct(Subtype $model)
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
     * @return Subtype
     */
    public function create(array $attributes): Subtype
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Subtype|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Subtype
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Subtype not found", 404);
        }

        return $model;
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return Subtype
     * @throws ModelNotFoundException
     */
    public function update(array $attributes, int $id): Subtype
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Subtype not found", 404);
        }

        $model->update($attributes);

        return $model;
    }
}
