<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use RodrigoPedra\RecordProcessor\Contracts\Reader;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordParser;
use RodrigoPedra\RecordProcessor\Examples\RecordObjects\ExampleRecord;

class ExampleLaravelBuilderParser implements RecordParser
{
    /**
     * @param  \RodrigoPedra\RecordProcessor\Contracts\Reader  $reader
     * @param  \Illuminate\Database\Eloquent\Model|\stdClass  $rawContent
     * @return \RodrigoPedra\RecordProcessor\Contracts\Record
     */
    public function parseRecord(Reader $reader, $rawContent): Record
    {
        return new ExampleRecord($rawContent->name, [
            'name' => $rawContent->name,
            'email' => $rawContent->email,
        ]);
    }
}
