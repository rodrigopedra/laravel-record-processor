<?php

namespace RodrigoPedra\LaravelRecordProcessor\RecordSerializers;

use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\LaravelRecordProcessor\Records\EloquentRecord;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordSerializer;
use RodrigoPedra\RecordProcessor\Contracts\Serializer;

class EloquentRecordSerializer implements RecordSerializer
{
    protected Builder $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function serializeRecord(Serializer $serializer, Record $record): bool
    {
        if (! $record->isValid()) {
            return false;
        }

        if ($record instanceof EloquentRecord) {
            $serializer->append($record->model());
        } else {
            $serializer->append($this->builder->make($record->toArray()));
        }

        return true;
    }
}
