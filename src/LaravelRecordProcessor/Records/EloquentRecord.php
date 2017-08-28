<?php

namespace RodrigoPedra\LaravelRecordProcessor\Records;

use Illuminate\Database\Eloquent\Model;
use RodrigoPedra\RecordProcessor\Contracts\Record;
use function RodrigoPedra\RecordProcessor\value_or_null;

class EloquentRecord implements Record
{
    /** @var  Model */
    protected $model;

    public function __construct( Model $model )
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getField( $field, $default = '' )
    {
        $value = $this->model->getAttributeValue( $field );

        return value_or_null( $value ) ?: $default;
    }

    public function valid()
    {
        $valid = $this->model->getAttributeValue( 'valid' );

        return is_bool( $valid ) ? $valid : true;
    }

    public function toArray()
    {
        return $this->model->toArray();
    }

    public function getKey()
    {
        return $this->model->getKey();
    }
}
