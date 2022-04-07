<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $attributes
     * @return Card
     */
    public function create(array $attributes): User;

    /**
     * @param $id
     * @return Card|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?User;
}
