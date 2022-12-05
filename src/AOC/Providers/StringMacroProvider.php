<?php

namespace AOC\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Stringable;
use Illuminate\Support\Str;

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
                    ->filter(fn($item, $key) => is_string($key));
            } else {
                return collect($matches);
            }
        });

        Stringable::macro('characters', function() {
            /** @var Illuminate\Support\Stringable $this */
            return $this->split(1);
        });

        Stringable::macro('toDecimal', function($from) {
            /** @var Illuminate\Support\Stringable $this */
            return (int)base_convert((string)$this, $from, 10);
        });

        Stringable::macro('indexInAlphabet', function() {
            /** @var Illuminate\Support\Stringable $this */
            return ord($this->lower()) - ord('a');
        });

        Stringable::macro('isUpper', function() {
            /** @var Illuminate\Support\Stringable $this */
            return $this == $this->upper();
        });

        Stringable::macro('splitIn', function($count) {
            /** @var Illuminate\Support\Stringable $this */
            $chunk = ceil($this->length / $count);
            return collect(range(0, $this->length - 1, $chunk))
                ->map(fn ($i) => $this->substr($i, $chunk));
        });
    }
}