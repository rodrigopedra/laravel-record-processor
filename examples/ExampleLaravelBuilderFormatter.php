<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\Writer;
use RodrigoPedra\RecordProcessor\Contracts\RecordFormatter;
use RodrigoPedra\RecordProcessor\Records\RecordKeyAggregate;

class ExampleLaravelBuilderFormatter implements RecordFormatter
{
    /**
     * Encode Record objects content to writer format
     *
     * @param  Writer  $writer
     * @param  Record  $record
     * @return bool
     */
    public function formatRecord(Writer $writer, Record $record)
    {
        if (! $record->valid()) {
            return false;
        }

        $data = $record->toArray();

        if ($record instanceof RecordKeyAggregate) {
            $data = [
                'name' => $record->getKey(),
                'email' => implode(', ', array_map(
                    function (Record $record) {
                        return $record->get('email');
                    }, $record->getRecords())),
            ];
        }

        $writer->append(new UserEloquentModel($data));

        return true;
    }
}
