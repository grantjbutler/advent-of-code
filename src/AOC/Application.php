<?php

namespace AOC;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class Application extends Container {
    protected string $basePath;

    public function __construct(string $basePath) {
        $this->basePath = $basePath;

        $this->registerBaseBindings();

        $this->registerCoreContainerAliases();

        $this->registerMacros();
    }

    protected function registerBaseBindings() {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);

        $this->singleton('events', function($app) {
            return new Dispatcher($app);
        });
    }

    protected function registerMacros() {
        Collection::macro('asIntegers', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->map(function ($item) {
                if ($item instanceof Stringable) {
                    return (int)$item->__toString();
                }
                
                return (int)$item;
            });
        });

        Collection::macro('characters', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->map(fn ($item) => $item->split(1));
        });

        Collection::macro('maxKey', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->reduce(function($carry, $value, $key) {
                if ($carry[1] == null || $value > $carry[1]) {
                    return [$key, $value];
                }

                return $carry;
            }, ['', null])[0];
        });

        Collection::macro('minKey', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->reduce(function($carry, $value, $key) {
                if ($carry[1] == null || $value < $carry[1]) {
                    return [$key, $value];
                }

                return $carry;
            }, ['', null])[0];
        });

        Collection::macro('columns', function($size) {
            /** @var Illuminate\Support\Collection $this */
            /** @var Illuminate\Support\Collection $self */
            $self = $this;
            return collect()->range(0, $size - 1)
                ->map(function($index) use ($self) {
                    return $self->nth(5, $index);
                });
        });

        Collection::macro('rows', function($size) {
            /** @var Illuminate\Support\Collection $this */
            return $this->chunk($size);
        });

        Collection::macro('mapFirst', function($block) {
            /** @var Illuminate\Support\Collection $this */
            foreach ($this as $item) {
                $result = $block($item);
                if ($result) {
                    return $result;
                }
            }

            return null;
        });
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