<?php

namespace AOC2021\Day2;

use AOC\Day;
use AOC\Input;
use AOC\Geometry\Point;

class Day2 extends Day {
    public function part1(Input $input) {
        $location = $input->lines
            ->reduce(function($location, $line) {
                [$command, $arg] = explode(" ", $line);
                
                switch ($command) {
                    case "forward":
                        $location->x += (int)$arg;
                        break;
                    case "down":
                        $location->y += (int)$arg;
                        break;
                    case "up":
                        $location->y -= (int)$arg;
                        break;
                }

                return $location;
            }, new Point(0, 0));

        return $location->x * $location->y;
    }

    public function part2(Input $input) {
        $sub = $input->lines
            ->reduce(function($sub, $line) {
                [$command, $arg] = explode(" ", $line);
                $sub->{$command}((int)$arg);
                
                return $sub;
            }, new Submarine());
        return $sub->position * $sub->depth;
    }
}

class Submarine {
    public int $position = 0;
    public int $depth = 0;
    public int $aim = 0;

    public function forward(int $amount) {
        $this->position += $amount;
        $this->depth += $amount * $this->aim;
    }

    public function up(int $amount) {
        $this->aim -= $amount;
    }

    public function down(int $amount) {
        $this->aim += $amount;
    }
}