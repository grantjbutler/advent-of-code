<?php

namespace AOC2021\Day6;

use AOC\Day;
use AOC\Input;

class Day6 extends Day {
    public function part1(Input $input) {
        return $this->runSimulation($input, 80);
    }

    public function part2(Input $input) {
        return $this->runSimulation($input, 256);
    }

    private function runSimulation(Input $input, int $days) {
        $fish = collect(
            explode(',', $input->lines->first())
        )
        ->map(fn ($num) => (int)$num)
        ->mapInto(Lanterfish::class);

        return collect()->range(1, $days)
            ->reduce(function($fish, $day) {
                return $fish->flatMap(fn ($fish) => $fish->age());
            }, $fish)
            ->count();
    }
}

class Lanterfish {
    public int $age;

    public function __construct(int $age) {
        $this->age = $age;
    }

    public function age() {
        if ($this->age == 0) {
            $this->age = 6;
            return collect([$this, new Lanterfish(8)]);
        }

        $this->age -= 1;
        return collect([$this]);
    }
}