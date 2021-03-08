<?php

namespace RodrigoPedra\LaravelRecordProcessor;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use RodrigoPedra\RecordProcessor\ProcessorBuilder as BaseProcessorBuilder;

class LaravelRecordProcessorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind(ProcessorBuilder::class, function () {
            $processor = new ProcessorBuilder();

            $processor->setLogger($this->app->get('log'));

            return $processor;
        });

        $this->app->alias(ProcessorBuilder::class, BaseProcessorBuilder::class);
    }

    public function provides(): array
    {
        return [
            ProcessorBuilder::class,
            BaseProcessorBuilder::class,
        ];
    }
}
