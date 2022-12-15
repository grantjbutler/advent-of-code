<?php

namespace AOC\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class CollectionMacroProvider extends Provider {
    function boot() {
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
            return $this->map(fn ($item) => $item->characters());
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
            return collect()->range(0, $size - 1)
                ->map(fn($index) => $this->nth($size, $index));
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

        Collection::macro('product', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->reduce(fn ($total, $value) => $total * $value, 1);
        });

        Collection::macro('commonElements', function() {
            /** @var Illuminate\Support\Collection $this */
            return $this->skip(1)->reduce(function($common, $elements) {
                return $common->intersect($elements)->values();
            }, $this->first());
        });

        Collection::macro('firstIndex', function($callback) {
            /** @var Illuminate\Support\Collection $this */
            return $this->zip($this->keys())
                ->first(fn ($pair) => $callback($pair[0]))[1];
        });
    }
}