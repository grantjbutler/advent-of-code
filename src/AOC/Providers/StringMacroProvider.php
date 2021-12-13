<?php

namespace AOC\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;

class StringMacroProvider extends Provider {
    function boot() {
        Stringable::macro('matches', function($pattern) {
            /** @var Illuminate\Support\Stringable $this */
            preg_match($pattern, $this->__toString(), $matches);

            if (empty($matches)) {
                return null;
            }

            if (Arr::isAssoc($matches)) {
                return collect($matches)
                    ->filter(function($item, $key) {
                        return is_string($key);
                    });
            } else {
                return collect($matches);
            }
        });

        Stringable::macro('characters', function() {
            /** @var Illuminate\Support\Stringable $this */
            return $this->split(1);
        });
    }
}