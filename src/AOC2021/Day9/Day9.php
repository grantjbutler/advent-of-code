<?php

namespace AOC2021\Day9;

use AOC\Day;
use AOC\Input;

class Day9 extends Day {
    public function part1(Input $input) {
        $map = $input->lines
            ->flatMap(function($line) {
                return $line->split(1)
                    ->asIntegers();
            });
        
        $width = $input->lines->first()->length();

        return $map->filter(fn ($height, $key) => $this->adjacentIndicies($key, $width, $map->count())->every(fn ($index) => $map[$index] > $height))
            ->map(fn ($height) => $height + 1)
            ->sum();
    }

    public function part2(Input $input) {
        $map = $input->lines
            ->flatMap(function($line) {
                return $line->split(1)
                    ->asIntegers();
            });
        
        $width = $input->lines->first()->length();
        $self = $this;
        return $map->filter(fn ($height, $key) => $this->adjacentIndicies($key, $width, $map->count())->every(fn ($index) => $map[$index] > $height))
            ->map(function($height, $key) use ($self, $width, $map) {
                $indiciesToCheck = collect([$key]);
                $basinIndicies = collect([$key]);
                
                while ($indiciesToCheck->count()) {
                    $index = $indiciesToCheck->shift();
                    $height = $map[$index];
                    
                    $newIndicies = $self->adjacentIndicies($index, $width, $map->count())
                        ->filter(fn ($value) => $map[$value] != 9 && $map[$value] >= $height && !$basinIndicies->contains($value));
                    $basinIndicies = $basinIndicies->concat($newIndicies);
                    
                    $indiciesToCheck = $indiciesToCheck->concat($newIndicies);
                }
                
                return $basinIndicies->count();
            })
            ->sortDesc()
            ->take(3)
            ->reduce(function($total, $value) {
                return $total * $value;
            }, 1);
    }

    private function adjacentIndicies($key, $width, $length) {
        return collect([$key - 1, $key + 1, $key - $width, $key + $width])
            ->filter(fn ($index) => $index >= 0 && $index < $length);
    }
}