<?php

namespace RodrigoPedra\LaravelRecordProcessor\Writers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use RodrigoPedra\RecordProcessor\Contracts\ConfigurableWriter;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;
use RodrigoPedra\RecordProcessor\Helpers\WriterConfigurator;
use RodrigoPedra\RecordProcessor\Traits\CountsLines;
use RuntimeException;
use function RodrigoPedra\RecordProcessor\is_associative_array;

class EloquentWriter implements ConfigurableWriter
{
    use CountsLines;

    /** @var  Builder */
    protected $writer;

    /** @var  string */
    protected $keyName;

    /** @var Collection|null */
    protected $results = null;

    /** @var bool */
    protected $shouldOutputModels = false;

    public function __construct( Builder $eloquentBuilder )
    {
        $this->writer  = $eloquentBuilder;
        $this->keyName = $this->writer->getModel()->getKeyName();

        // default values
        $this->setShouldOutputModels( false );
    }

    /**
     * @param bool $shouldOutputModels
     */
    public function setShouldOutputModels( $shouldOutputModels )
    {
        $this->shouldOutputModels = $shouldOutputModels;
    }

    /**
     * @return Builder
     */
    public function getEloquentBuilder()
    {
        return $this->writer;
    }

    public function open()
    {
        $this->lineCount = 0;

        $this->results = $this->hasOutput() ? new Collection : null;
    }

    public function close()
    {
        //
    }

    public function append( $content )
    {
        if (!is_associative_array( $content )) {
            throw new RuntimeException( 'content for EloquentWriter should be an associative array' );
        }

        $key = array_get( $content, $this->keyName, null );

        $model = is_null( $key )
            ? $this->writer->newModelInstance()
            : $this->writer->findOrNew( $key );

        $model->fill( $content )->save();

        if ($this->hasOutput()) {
            $this->results->push( $model );
        }

        $this->incrementLineCount();
    }

    /**
     * @return array
     */
    public function getConfigurableMethods()
    {
        return [
            'getEloquentBuilder',
            'setShouldOutputModels',
        ];
    }

    /**
     * @return Configurator
     */
    public function createConfigurator()
    {
        return new WriterConfigurator( $this, false, false );
    }

    /**
     * @return bool
     */
    public function hasOutput()
    {
        return $this->shouldOutputModels;
    }

    /**
     * @return mixed
     */
    public function output()
    {
        return $this->results;
    }
}
