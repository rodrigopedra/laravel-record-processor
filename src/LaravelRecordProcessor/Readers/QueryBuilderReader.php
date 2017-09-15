<?php

namespace RodrigoPedra\LaravelRecordProcessor\Readers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use RodrigoPedra\RecordProcessor\Contracts\ConfigurableReader;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;
use RodrigoPedra\RecordProcessor\Traits\CountsLines;
use RodrigoPedra\RecordProcessor\Traits\Readers\HasInnerIterator;

class QueryBuilderReader implements ConfigurableReader
{
    use CountsLines, HasInnerIterator;

    /** @var  Builder */
    protected $queryBuilder;

    /** @var Model */
    protected $currentRecord = false;

    public function __construct( Builder $eloquentBuilder )
    {
        $this->queryBuilder = $eloquentBuilder;
    }

    public function open()
    {
        $this->lineCount = 0;

        $this->setInnerIterator( $this->queryBuilder->get()->getIterator() );
    }

    public function close()
    {
        $this->setInnerIterator( null );
    }

    /**
     * @return Builder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @return array
     */
    public function getConfigurableMethods()
    {
        return [ 'getQueryBuilder' ];
    }

    /**
     * @return Configurator
     */
    public function createConfigurator()
    {
        return new Configurator( $this );
    }
}
