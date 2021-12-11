<?php

namespace AOC2021\Day9;

use AOC\Day;
use AOC\Input;

class Day9 extends Day {
    public function part1(Input $input) {
        $matrix = $input->matrix;
        
        return $matrix->filter(fn ($height, $key) => $matrix->adjacent($key)->every(fn ($index) => $matrix->get($index) > $height))
            ->values()
            ->map(fn ($height) => $height + 1)
            ->sum();
    }

    public function part2(Input $input) {
        $matrix = $input->matrix;
        
        return collect(
            $matrix->filter(fn ($height, $key) => $matrix->adjacent($key)->every(fn ($index) => $matrix->get($index) > $height))
                ->map(function($key, $height) use ($matrix) {
                    $indiciesToCheck = collect([$key]);
                    $basinIndicies = collect([$key]);
                    
                    while ($indiciesToCheck->count()) {
                        $index = $indiciesToCheck->shift();
                        $height = $matrix->get($index);
                        
                        $newIndicies = $matrix->adjacent($index)
                            ->filter(fn ($value) => $matrix->get($value) != 9 && $matrix->get($value) >= $height && !$basinIndicies->contains($value));
                        $basinIndicies = $basinIndicies->concat($newIndicies);
                        
                        $indiciesToCheck = $indiciesToCheck->concat($newIndicies);
                    }
                    
                    return $basinIndicies->count();
                })
                ->values()
            )
            ->sortDesc()
            ->take(3)
            ->reduce(function($total, $value) {
                return $total * $value;
            }, 1);
    }
}