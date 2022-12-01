<?php

namespace AOC2022\Day1;

use AOC\Day;
use AOC\Input;

class Day1 extends Day {
    public function part1(Input $input) {
        return $input->explode("\n\n")
            ->map(function($inventory) {
                return collect(explode("\n", $inventory))
                    ->reduce(function($total, $item) {
                        return $total + (int)$item;
                    }, 0);

            })
            ->max();
    }

    public function part2(Input $input) {
        return $input->explode("\n\n")
            ->map(function($inventory) {
                return collect(explode("\n", $inventory))
                    ->reduce(function($total, $item) {
                        return $total + (int)$item;
                    }, 0);

            })
            ->sortDesc()
            ->take(3)
            ->sum();
    }
}