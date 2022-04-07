<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected User $model;

    /**
     * UserRepository constructor.
     *
     * @param Image $model
     */
    public function __construct(User $model)
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
     * @return User
     */
    public function create(array $attributes): User
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return User|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): ?User
    {
        if (null == $model = $this->model->find($id)) {
            throw new ModelNotFoundException("User not found", 404);
        }

        return $model;
    }
}
