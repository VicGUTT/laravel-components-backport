<?php

namespace VicGutt\ComponentBackport\Providers;

use VicGutt\ComponentBackport\View\Engines\PhpEngine;
use VicGutt\ComponentBackport\View\ViewServiceProvider;

class ComponentBackportServiceProvider extends ViewServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->registerCommands();
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param  \VicGutt\ComponentBackport\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () {
            return new PhpEngine($this->app['files']);
        });
    }


    /**
     * Register necessary commands.
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands([
            \VicGutt\ComponentBackport\Console\Commands\ComponentMakeCommand::class,
        ]);
    }
}
