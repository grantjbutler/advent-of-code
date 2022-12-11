<?php

namespace AOC\Parsing;

use Illuminate\Support\Stringable;
use Illuminate\Support\Str;

class StringParser {
    private int $cursor = 0;
    private Stringable $string;

    function __construct(Stringable|string $input) {
        if (is_string($input)) {
            $this->string = Str::of($input);
        } else {
            $this->string = $input;
        }
    }

    public function read(string|int $input): mixed {
        if (is_string($input)) {
            if (!$this->peek($input)) { return false; }
            $this->cursor += strlen($input);
            return Str::of($input);
        } else {
            $string = $this->string->substr($this->cursor, $input);
            $this->cursor += $input;
            return $string;
        }
    }

    public function readRegex(string $regex): mixed {
        if (($matches = $this->string->matches($regex, $this->cursor))) {
            $this->cursor += strlen($matches['<original>']);
            return $matches;
        }

        return false;
    }

    public function peek(string|int $input): mixed {
        if (is_string($input)) {
            return $this->string->substr($this->cursor, strlen($input)) == $input;
        } else {
            return $this->string->substr($this->cursor, $input);
        }
    }
}