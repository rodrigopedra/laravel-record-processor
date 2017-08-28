<?php

namespace RodrigoPedra\LaravelRecordProcessor\Records\Formatter;

use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use RodrigoPedra\RecordProcessor\Contracts\RecordFormatter;
use RodrigoPedra\RecordProcessor\Contracts\Writer;

class EloquentRecordFormatter implements RecordFormatter
{
    /** @var Model */
    protected $model;

    public function __construct( Model $model )
    {
        $this->model = $model;
    }

    public function formatRecord( Writer $writer, Record $record )
    {
        if (!$record->valid()) {
            return false;
        }

        $writer->append( $this->model->newInstance( $record->toArray() ) );

        return true;
    }
}
