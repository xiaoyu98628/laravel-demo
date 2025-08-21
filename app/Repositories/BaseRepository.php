<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class BaseRepository
{
    protected mixed $model;

    private EloquentBuilder $query;

    public function query(): EloquentBuilder
    {
        $this->query = $this->model::query();

        return $this->query;
    }

    public function withTrashed(): EloquentBuilder|QueryBuilder
    {
        $this->query = $this->model::withTrashed();

        return $this->query;
    }

    public function onlyTrashed(): EloquentBuilder|QueryBuilder
    {
        $this->query = $this->model::onlyTrashed();

        return $this->query;
    }

    public function newQuery(): Model|EloquentBuilder
    {
        return $this->query->newModelInstance();
    }
}
