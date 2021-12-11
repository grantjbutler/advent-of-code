<?php

namespace AOC2021\Day11;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day11 extends Day {
    public function part1(Input $input) {
        $lines = $input->lines
            ->characters()
            ->flatMap->asIntegers();
        
        $width = $input->lines[0]->length();
        $size = $lines->count();

        $flashed = 0;
        for ($i = 0; $i < 100; $i++) {
            [$lines, $count] = $this->step($lines, $size, $width);
            $flashed += $count;
        }
        return $flashed;
    }

    public function part2(Input $input) {
        $lines = $input->lines
            ->characters()
            ->flatMap->asIntegers();
        
        $width = $input->lines[0]->length();
        $size = $lines->count();

        $i = 1;
        while (true) {
            [$lines, $count] = $this->step($lines, $size, $width);
            if ($count == $size) {
                return $i;
            }
            $i++;
        }
    }

    private function step(Collection $collection, int $length, int $width) {
        $collection = $collection->map(fn ($item) => $item + 1);

        $flashed = collect();
        $collection->filter(fn ($item) => $item > 9)
            ->keys()
            ->each(fn ($key) => $this->flash($key, $collection, $flashed, $length, $width));

        return [$collection->map(fn ($item) => $item > 9 ? 0 : $item), $flashed->count()];
    }

    private function flash(int $key, Collection $collection, Collection $flashed, int $length, int $width) {
        if ($flashed->contains($key)) {
            return;
        }

        $flashed->push($key);

        $self = $this;
        $this->adjacentIndicies($key, $width, $length)
            ->each(fn ($key) => $collection[$key] += 1)
            ->each(function ($key) use ($collection, $self, $flashed, $length, $width) {
                if ($collection[$key] > 9) {
                    $self->flash($key, $collection, $flashed, $length, $width);
                }
            });
    }

    private function adjacentIndicies($key, $width, $length) {
        $indicies = collect([$key - $width, $key + $width]);

        if ($key % $width > 0) {
            $indicies->push($key - $width - 1, $key - 1, $key + $width - 1);
        }

        if ($key % $width < $width - 1) {
            $indicies->push($key - $width + 1, $key + 1, $key + $width + 1);
        }

        return $indicies->filter(fn ($index) => $index >= 0 && $index < $length);
    }

    private function toString($collection, $width) {
        return $collection->chunk($width)
            ->map->join('')
            ->join("\n");
    }
}