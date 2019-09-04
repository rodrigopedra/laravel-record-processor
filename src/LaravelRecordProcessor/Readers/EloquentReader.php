<?php

namespace RodrigoPedra\LaravelRecordProcessor\Readers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RodrigoPedra\RecordProcessor\Traits\CountsLines;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;
use RodrigoPedra\RecordProcessor\Contracts\ConfigurableReader;
use RodrigoPedra\RecordProcessor\Traits\Readers\HasInnerIterator;

class EloquentReader implements ConfigurableReader
{
    use CountsLines, HasInnerIterator;

    /** @var  Builder */
    protected $eloquentBuilder;

    /** @var Model */
    protected $currentRecord = false;

    public function __construct(Builder $eloquentBuilder)
    {
        $this->eloquentBuilder = $eloquentBuilder;
    }

    public function open()
    {
        $this->lineCount = 0;

        $this->setInnerIterator($this->eloquentBuilder->get()->getIterator());
    }

    public function close()
    {
        $this->setInnerIterator(null);
    }

    /**
     * @return Builder
     */
    public function getEloquentBuilder()
    {
        return $this->eloquentBuilder;
    }

    /**
     * @return array
     */
    public function getConfigurableMethods()
    {
        return ['getEloquentBuilder'];
    }

    /**
     * @return Configurator
     */
    public function createConfigurator()
    {
        return new Configurator($this);
    }
}


