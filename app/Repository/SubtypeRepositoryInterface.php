<?php

namespace App\Repository;

use App\Models\Subtype;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface SubtypeRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $attributes
     * @return Subtype
     */
    public function create(array $attributes): Subtype;

    /**
     * @param $id
     * @return Subtype|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Subtype;

    /**
     * @param array $attributes
     * @param int $id
     * @return Subtype
     */
    public function update(array $attributes, int $id): Subtype;
}
