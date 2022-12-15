<?php

namespace AOC2021\Day1;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day1 extends Day {
    public function part1(Input $input) {
        return $this->countIncreased($input->lines->asIntegers());
    }

    public function part2(Input $input) {
        return $this->countIncreased(
            $input->lines
                ->asIntegers()
                ->sliding(3)
                ->map->sum()
        );
    }

    private function countIncreased(Collection $ints) {
        return $ints->reduce(function($carry, $depth) {
            if ($carry['previous'] == null) {
                $carry['previous'] = $depth;
                return $carry;
            }

            if ($depth > $carry['previous']) {
                $carry['count'] += 1;
            }

            $carry['previous'] = $depth;

            return $carry;
        }, ['count' => 0, 'previous' => null])['count'];
    }
}