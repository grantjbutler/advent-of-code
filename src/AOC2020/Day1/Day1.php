<?php

namespace AOC2020\Day1;

use AOC\Day;
use AOC\Input;
use Ds\Map;

class Day1 extends Day {
    public function part1(Input $input) {
        $numbers = $input->lines
            ->asIntegers();

        $storage = new Map();
        foreach ($input->lines->asIntegers()->all() as $number) {
            if ($storage->hasKey($number)) {
                return $number * $storage->get($number);
            } else {
                $storage->put(2020 - $number, $number);
            }
        }
    }

    public function part2(Input $input) {
        $numbers = $input->lines
            ->asIntegers();

        $mapping = $numbers
            ->reduce(function ($storage, $number) {
                $storage->put(2020 - $number, $number);
                return $storage;
            }, new Map());
        
        foreach ($numbers->crossJoin(collect($mapping->keys()))->all() as $pair) {
            $third = $pair[1] - $pair[0];
            if ($numbers->contains($third)) {
                return $pair[0] * $mapping->get($pair[1]) * $third;
            }
        }
    }
}