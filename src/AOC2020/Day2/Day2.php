<?php

namespace AOC2020\Day2;

use AOC\Day;
use AOC\Input;

class Day2 extends Day {
    public function part1(Input $input) {
        return $input->lines
            ->map(function ($line) {
                return $line->matches('/(?<min>[0-9]+)-(?<max>[0-9]+) (?<letter>[a-z]): (?<password>[a-z]+)/');
            })
            ->filter(function($entry) {
                $count = substr_count($entry['password'], $entry['letter']);
                return $count >= (int)$entry['min'] && $count <= (int)$entry['max'];
            })
            ->count();
    }

    public function part2(Input $input) {
        return $input->lines
            ->map(function ($line) {
                return $line->matches('/(?<min>[0-9]+)-(?<max>[0-9]+) (?<letter>[a-z]): (?<password>[a-z]+)/');
            })
            ->filter(function($entry) {
                $min = $entry['password'][((int)$entry['min']) - 1];
                $max = $entry['password'][((int)$entry['max']) - 1];

                return ($min == $entry['letter'] || $max == $entry['letter']) && $min != $max;
            })
            ->count();
    }
}