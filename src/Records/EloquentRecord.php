<?php

namespace RodrigoPedra\LaravelRecordProcessor\Records;

use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\RecordProcessor\Contracts\Record;

final class EloquentRecord implements Record
{
    public function __construct(
        private readonly Model $model,
    ) {}

    public function model(): Model
    {
        return $this->model;
    }

    public function key(): mixed
    {
        return $this->model->getKey();
    }

    public function field(string $field, $default = null)
    {
        if (! $this->model->hasAttribute($field)) {
            return \value($default);
        }

        return $this->model->getAttributeValue($field);
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
