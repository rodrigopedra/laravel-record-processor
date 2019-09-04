<?php

namespace RodrigoPedra\LaravelRecordProcessor\Records\Parsers;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;
use RodrigoPedra\LaravelRecordProcessor\Records\EloquentRecord;

class EloquentRecordParser implements RecordParser
{
    public function parseRecord(Reader $reader, $model)
    {
        if (! $model instanceof Model) {
            throw new RuntimeException('content for EloquentRecordParser should be an Eloquent Model');
        }

        return new EloquentRecord($model);
    }
}
