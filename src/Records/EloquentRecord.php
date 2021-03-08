<?php

namespace RodrigoPedra\LaravelRecordProcessor\Records;

use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\RecordProcessor\Contracts\Record;

class EloquentRecord implements Record
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function model(): Model
    {
        return $this->model;
    }

    public function key()
    {
        return $this->model->getKey();
    }

    public function field(string $field)
    {
        return $this->model->getAttribute($field);
    }

    public function isValid(): bool
    {
        return $this->model->exists;
    }

    public function toArray(): array
    {
        return $this->model->toArray();
    }
}
