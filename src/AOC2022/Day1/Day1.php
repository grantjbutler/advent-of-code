<?php

namespace AOC2022\Day1;

use AOC\Day;
use AOC\Input;

class Day1 extends Day {
    public function part1(Input $input) {
        return $input->groups
            ->map(function($inventory) {
                return $inventory->explode("\n")
                    ->asIntegers()
                    ->sum();
            })
            ->max();
    }

    public function part2(Input $input) {
        return $input->groups
            ->map(function($inventory) {
                return $inventory->explode("\n")
                ->asIntegers()
                    ->sum();
            })
            ->sortDesc()
            ->take(3)
            ->sum();
    }
}