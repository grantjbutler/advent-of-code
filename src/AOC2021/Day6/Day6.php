<?php

namespace AOC2021\Day6;

use AOC\Day;
use AOC\Input;
use Ds\Map;

class Day6 extends Day {
    public function part1(Input $input) {
        return $this->runSimulation($input, 80);
    }

    public function part2(Input $input) {
        return $this->runSimulation($input, 256);
    }

    private function runSimulation(Input $input, int $days) {
        $fishAges = $input->explode(',')
        ->asIntegers()
        ->countBy()
        ->reduce(function ($ages, $item, $key) {
            $ages->put($key, $item);
            return $ages;
        }, new Map());
        
        return collect()->range(1, $days)
            ->reduce(function($ages, $day) {
                return $ages->reduce(function($newAges, $key, $value) {
                    if ($key == 0) {
                        $newAges->put(8, $newAges->get(8, 0) + $value);
                        $newAges->put(6, $newAges->get(6, 0) + $value);
                        return $newAges;
                    }

                    $newAge = ($key - 1);
                    $newAges->put($newAge, $newAges->get($newAge, 0) + $value);

                    return $newAges;
                }, new Map());
            }, $fishAges)
            ->reduce(function($totals, $key, $value) {
                return $totals + $value;
            }, 0);
    }
}