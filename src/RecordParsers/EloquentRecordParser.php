<?php

namespace RodrigoPedra\LaravelRecordProcessor\RecordParsers;

use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\LaravelRecordProcessor\Records\EloquentRecord;
use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;

class EloquentRecordParser implements RecordParser
{
    public function parseRecords(Reader $reader, $rawContent): Record
    {
        if (! $rawContent instanceof Model) {
            throw new \RuntimeException('content for EloquentRecordParser should be an Eloquent Model');
        }

        return new EloquentRecord($rawContent);
    }
}
