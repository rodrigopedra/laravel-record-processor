<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;
use RodrigoPedra\RecordProcessor\Examples\RecordObjects\ExampleRecord;

class ExampleLaravelBuilderParser implements RecordParser
{
    /**
     * Generates Record objects from raw data
     *
     * @param  Reader  $reader
     * @param  mixed  $rawContent
     * @return Record
     */
    public function parseRecord(Reader $reader, $rawContent)
    {
        return new ExampleRecord([
            'name' => $rawContent->name,
            'email' => $rawContent->email,
        ]);
    }
}
