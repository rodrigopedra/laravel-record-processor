<?php

namespace RodrigoPedra\LaravelRecordProcessor\Writers;

use Illuminate\Database\Query\Builder;
use RodrigoPedra\RecordProcessor\Contracts\ConfigurableWriter;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;
use RodrigoPedra\RecordProcessor\Helpers\WriterConfigurator;
use RodrigoPedra\RecordProcessor\Traits\CountsLines;
use RodrigoPedra\RecordProcessor\Traits\NoOutput;
use RuntimeException;
use function RodrigoPedra\RecordProcessor\is_associative_array;

class QueryBuilderWriter implements ConfigurableWriter
{
    use CountsLines, NoOutput;

    /** @var  Builder */
    protected $writer;

    /** @var  string */
    protected $keyName;

    public function __construct( Builder $queryBuilder )
    {
        $this->writer = $queryBuilder;
    }

    /**
     * @return Builder
     */
    public function getQueryBuilder()
    {
        return $this->writer;
    }

    public function open()
    {
        $this->lineCount = 0;
    }

    public function close()
    {
        //
    }

    public function append( $content )
    {
        if (!is_associative_array( $content )) {
            throw new RuntimeException( 'content for QueryBuilderWriter should be an associative array' );
        }

        if (!$this->writer->insert( $content )) {
            throw new RuntimeException( 'Could not write Query\Builder records' );
        }

        $this->incrementLineCount();
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
        return new WriterConfigurator( $this, false, false );
    }
}
