<?php

namespace AOC2022\Day3;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Str;

class Day3 extends Day {
    public function part1(Input $input) {
        return $input->lines
            ->flatMap(function($contents) {
                return $contents->splitIn(2)
                    ->map(fn ($compartment) => $compartment->characters()->unique())
                    ->commonElements()
                    ->map(fn ($str) => Str::of($str))
                    ->map(function($item) {
                        return $item->indexInAlphabet() + ($item->isUpper() ? 27 : 1);
                    });
            })
            ->sum();
    }

    public function part2(Input $input) {
        return $input->lines
            ->chunk(3)
            ->flatMap(function($group) {
                return $group->map(function($contents) {
                    return $contents->characters()->unique();
                })
                ->commonElements()
                ->map(fn ($str) => Str::of($str))
                ->map(function($item) {
                    return $item->indexInAlphabet() + ($item->isUpper() ? 27 : 1);
                });
            })
            ->sum();
    }
}