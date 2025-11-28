<?php

namespace RodrigoPedra\LaravelRecordProcessor\Examples;

use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordSerializer;
use RodrigoPedra\RecordProcessor\Contracts\Serializer;
use RodrigoPedra\RecordProcessor\Records\RecordKeyAggregate;

class ExampleLaravelBuilderSerializer implements RecordSerializer
{
    public function serializeRecord(Serializer $serializer, Record $record): bool
    {
        if (! $record->isValid()) {
            return false;
        }

        $data = $record->toArray();

        if ($record instanceof RecordKeyAggregate) {
            $data = [
                'name' => $record->key(),
                'email' => \implode(', ',
                    \array_map(static fn (Record $record) => $record->field('email'), $record->records())),
            ];
        }

        $serializer->append(new UserEloquentModel($data));

        return true;
    }
}
