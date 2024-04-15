<?php

namespace App\Infrastructure\Database\Repositories\BaseRepository;

use App\Application\Contracts\Out\Repositories\Base\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

abstract class BaseRepository implements IBaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<int,Model>
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findById(int $id): Model
    {
        return $this->model->query()->findOrFail($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->model->query()->where([
            'id' => $id
        ])->delete();
    }

    /**
     * @param array<int,mixed> $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->query()->create($attributes);
    }

    /**
     * @param int $id
     * @param array<int,mixed> $attributes
     * @return Model
     * @throws Throwable
     */
    public function update(int $id, array $attributes): Model
    {
        $model = $this->model->query()->find($id);
        $model->updateOrFail($attributes);
        return $model->refresh();
    }
}
