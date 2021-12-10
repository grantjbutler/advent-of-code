<?php

namespace AOC;

use AOC\Providers\CollectionMacroProvider;
use AOC\Providers\StringMacroProvider;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

class Application extends Container {
    protected string $basePath;

    public function __construct(string $basePath) {
        $this->basePath = $basePath;

        $this->registerBaseBindings();

        $this->registerCoreContainerAliases();

        $this->bootProviders();
    }

    protected function registerBaseBindings() {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);

        $this->singleton('events', function($app) {
            return new Dispatcher($app);
        });
    }

    protected function bootProviders() {
        (new CollectionMacroProvider())->boot();
        (new StringMacroProvider())->boot();
    }

    public function registerCoreContainerAliases()
    {
        $aliases = [
            'app'                  => [\Illuminate\Contracts\Container\Container::class],
            'events'               => [\Illuminate\Events\Dispatcher::class, \Illuminate\Contracts\Events\Dispatcher::class],
            // 'hash'                 => [\Illuminate\Contracts\Hashing\Hasher::class],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    public function getBasePath() {
        return $this->basePath;
    }
}