<?php

namespace AOC2022\Day6;

use AOC\Day;
use AOC\Input;

class Day6 extends Day {
    public function part1(Input $input) {
        $windows = $input
            ->lines[0]
            ->characters()
            ->sliding(4);
        
        $i = 0;
        foreach ($windows as $window) {
            if (count($window->countBy()->all()) == 4) {
                return $i + 4;
            }
            $i++;
        }
    }

    public function part2(Input $input) {
        $windows = $input
            ->lines[0]
            ->characters()
            ->sliding(14);
        
        $i = 0;
        foreach ($windows as $window) {
            if (count($window->countBy()->all()) == 14) {
                return $i + 14;
            }
            $i++;
        }
    }
}