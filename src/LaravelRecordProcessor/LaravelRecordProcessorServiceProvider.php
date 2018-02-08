<?php

namespace RodrigoPedra\LaravelRecordProcessor;

use Illuminate\Support\ServiceProvider;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseProcessorBuilder;

class LaravelRecordProcessorServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot() { }

    public function register()
    {
        $this->app->bind( ProcessorBuilder::class, function () {
            $processor = new ProcessorBuilder;

            $processor->setLogger( $this->app->get( 'log' ) );

            return $processor;
        } );

        $this->app->alias( ProcessorBuilder::class, BaseProcessorBuilder::class );
    }

    public function provides()
    {
        return [
            ProcessorBuilder::class,
            BaseProcessorBuilder::class,
        ];
    }
}
