<?php

namespace AOC;

use AOC\DataStructures\Matrix;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property \Illuminate\Support\Collection $lines
 * @property \AOC\DataStructures\Matrix $matrix
 */
class Input {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function explode($separator): Collection {
        return Str::of(file_get_contents($this->filename))
            ->explode($separator);
    }

    public function __get($name) {
        if ($name == "lines") {
            return collect(file($this->filename))
                ->map(fn($line) => Str::of($line)->trim());
        } else if ($name == "matrix") {
            return new Matrix(
                $this->lines
                    ->characters()
                    ->map->asIntegers()
            );
        }
    }
}