<?php

namespace AOC2022\Day4;

use AOC\Day;
use AOC\Input;
use AOC\DataStructures\ClosedRange;
use Illuminate\Support\Str;

class Day4 extends Day {
    public function part1(Input $input) {
        return $input->lines
            ->map(function ($pairs) {
                return $pairs->explode(',')->map(fn ($part) => Str::of($part));
            })
            ->map(function ($groups) {
                return $groups->map(function ($range) {
                    [$start, $end] = $range->explode('-');
                    return new ClosedRange((int)$start, (int)$end);
                });
            })
            ->filter(function ($groups) {
                return $groups[0]->contains($groups[1])
                    || $groups[1]->contains($groups[0]);
            })
            ->count();
    }

    public function part2(Input $input) {
        return $input->lines
            ->map(function ($pairs) {
                return $pairs->explode(',')->map(fn ($part) => Str::of($part));
            })
            ->map(function ($groups) {
                return $groups->map(function ($range) {
                    [$start, $end] = $range->explode('-');
                    return new ClosedRange((int)$start, (int)$end);
                });
            })
            ->filter(function ($groups) {
                return $groups[0]->overlaps($groups[1])
                    || $groups[1]->overlaps($groups[0]);
            })
            ->count();
    }
}