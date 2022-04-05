<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface ImageRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $attributes
     * @return Image
     */
    public function create(array $attributes): Image;

    /**
     * @param $id
     * @return Image|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Image;

    /**
     * @param array $attributes
     * @param int $id
     * @return Image
     */
    public function update(array $attributes, int $id): Image;

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int;
}
