<?php

namespace App\Repositories;

use App\Models\Card;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface CardRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $attributes
     * @return Card
     */
    public function create(array $attributes): Card;

    /**
     * @param $id
     * @return Card|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?Card;

    /**
     * @param array $attributes
     * @param int $id
     * @return Card
     */
    public function update(array $attributes, int $id): Card;

    /**
     * @param $id
     * @return int
     */
    public function delete($id): int;
}
