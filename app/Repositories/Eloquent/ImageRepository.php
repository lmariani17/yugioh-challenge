<?php

namespace App\Repositories\Eloquent;

use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ImageRepository implements ImageRepositoryInterface
{
    /**
     * @var Image
     */
    protected Image $model;

    /**
     * ImageRepository constructor.
     *
     * @param Image $model
     */
    public function __construct(Image $model)
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
     * @return Image
     */
    public function create(array $attributes): Image
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Image|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Image
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Image not found", 404);
        }

        return $model;
    }

    /**
     * @param array $attributes
     * @param int $id
     * @return Image
     * @throws ModelNotFoundException
     */
    public function update(array $attributes, int $id): Image
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("Image not found", 404);
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
