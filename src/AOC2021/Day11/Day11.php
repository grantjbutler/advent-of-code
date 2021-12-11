<?php

namespace AOC2021\Day11;

use AOC\Day;
use AOC\Input;
use AOC\Geometry\Point;
use AOC\DataStructures\Matrix;
use Illuminate\Support\Collection;

class Day11 extends Day {
    public function part1(Input $input) {
        $matrix = $input->matrix;

        $flashed = 0;
        for ($i = 0; $i < 100; $i++) {
            [$matrix, $count] = $this->step($matrix);
            $flashed += $count;
        }
        return $flashed;
    }

    public function part2(Input $input) {
        $matrix = $input->matrix;
        
        $i = 1;
        while (true) {
            [$matrix, $count] = $this->step($matrix);
            if ($count == $matrix->size) {
                return $i;
            }
            $i++;
        }
    }

    private function step(Matrix $matrix) {
        $matrix = $matrix->map(fn ($item) => $item + 1);

        $flashed = collect();
        foreach($matrix->filter(fn ($item) => $item > 9)->keys() as $key) {
            $this->flash($key, $matrix, $flashed);            
        }

        return [$matrix->map(fn ($item) => $item > 9 ? 0 : $item), $flashed->count()];
    }

    private function flash(Point $key, Matrix $matrix, Collection $flashed) {
        if ($flashed->contains($key)) {
            return;
        }

        $flashed->push($key);

        $self = $this;
        $matrix->adjacent($key, true)
            ->each(fn ($key) => $matrix->put($key, $matrix->get($key) + 1))
            ->each(function ($key) use ($matrix, $self, $flashed) {
                if ($matrix->get($key) > 9) {
                    $self->flash($key, $matrix, $flashed);
                }
            });
    }
}