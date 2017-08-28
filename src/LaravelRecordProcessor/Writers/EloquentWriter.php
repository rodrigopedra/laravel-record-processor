<?php

namespace RodrigoPedra\LaravelRecordProcessor\Writers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RodrigoPedra\RecordProcessor\Contracts\ConfigurableWriter;
use RodrigoPedra\RecordProcessor\Helpers\Configurator;
use RodrigoPedra\RecordProcessor\Helpers\WriterConfigurator;
use RodrigoPedra\RecordProcessor\Traits\CountsLines;
use RuntimeException;

class EloquentWriter implements ConfigurableWriter
{
    use CountsLines;

    /** @var Builder */
    private $eloquentBuilder;

    /** @var Collection|null */
    protected $results = null;

    /** @var bool */
    protected $shouldOutputModels = false;

    public function __construct( Builder $eloquentBuilder )
    {
        $this->eloquentBuilder = $eloquentBuilder;

        // default values
        $this->outputModels( false );
    }

    public function getEloquentBuilder()
    {
        return $this->eloquentBuilder;
    }

    /**
     * @param bool $shouldOutputModels
     */
    public function outputModels( $shouldOutputModels )
    {
        $this->shouldOutputModels = !!$shouldOutputModels;
    }

    public function open()
    {
        $this->lineCount = 0;

        $this->results = $this->shouldOutputModels ? new Collection : null;
    }

    public function close()
    {
        //
    }

    public function append( $model )
    {
        if (!$model instanceof Model) {
            throw new RuntimeException( 'content for EloquentWriter should be Eloquent Model' );
        }

        $newModel = $this->eloquentBuilder->findOrNew( $model->getKey() );
        $newModel->setRawAttributes( $model->getAttributes() )->save();

        if ($this->shouldOutputModels) {
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
            'outputModels',
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
     * @return mixed
     */
    public function output()
    {
        return $this->results;
    }
}
