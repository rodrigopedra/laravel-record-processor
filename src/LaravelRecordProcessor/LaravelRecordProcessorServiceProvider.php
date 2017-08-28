<?php

namespace RodrigoPedra\LaravelRecordProcessor;

use Illuminate\Support\ServiceProvider;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseProcessorBuilder;

class LaravelRecordProcessorServiceProvider extends ServiceProvider
{
    public function boot() { }

    public function register()
    {
        if (!$this->app->bound( 'excel' )) {
            $this->app->register( \Maatwebsite\Excel\ExcelServiceProvider::class );
        }

        $this->app->bind( ProcessorBuilder::class, function () {
            return new ProcessorBuilder;
        } );

        $this->app->alias( ProcessorBuilder::class, BaseProcessorBuilder::class );
    }
}
