<?php

namespace AOC;

/**
 * @property Illuminate\Support\Collection $lines
 */
class Input {
    private $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function __get($name) {
        if ($name == "lines") {
            return collect(file($this->filename))
                ->map(function($item) { return trim($item); });
        }
    }
}