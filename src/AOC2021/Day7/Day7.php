<?php

namespace AOC2021\Day7;

use AOC\Day;
use AOC\Input;
use Ds\Map;

class Day7 extends Day {
    public function part1(Input $input) {
        $positions = $input->explode(',')
            ->asIntegers();

        $idealPosition = $positions->median();
        
        return $positions->reduce(function ($total, $position) use ($idealPosition) {
            return $total + abs($position - $idealPosition);
        }, 0);
    }

    public function part2(Input $input) {
        $positions = $input->explode(',')
            ->asIntegers();
        
        return collect()
            ->range($positions->min(), $positions->max())
            ->min(function ($position) use ($positions) {
                return $positions->reduce(function($total, $crab) use ($position) {
                    $distance = abs($position - $crab);
                    return $total + ($distance * ($distance + 1)) / 2;
                }, 0);
            });
    }
}