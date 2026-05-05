<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;
use RodrigoPedra\RecordProcessor\Examples\RecordObjects\ExampleRecord;

class ExampleLaravelBuilderParser implements RecordParser
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model|\stdClass  $rawContent
     */
    public function parseRecords(Reader $reader, $rawContent): Record
    {
        return new ExampleRecord($rawContent->name, [
            'name' => $rawContent->name,
            'email' => $rawContent->email,
        ]);
    }
}
