<?php

namespace AOC2022\Day6;

use AOC\Day;
use AOC\Input;

class Day6 extends Day {
    public function part1(Input $input) {
        return $input
            ->characters
            ->sliding(4)
            ->firstIndex(fn ($window) => $window->duplicates()->isEmpty()) + 4;
    }

    public function part2(Input $input) {
        return $input
            ->characters
            ->sliding(14)
            ->firstIndex(fn ($window) => $window->duplicates()->isEmpty()) + 14;
    }
}