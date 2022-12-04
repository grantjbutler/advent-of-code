<?php

namespace AOC2022\Day4;

use AOC\Day;
use AOC\Input;
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
                    return collect()->range((int)$start, (int)$end);
                });
            })
            ->filter(function ($groups) {
                return $groups[0]->diff($groups[1])->isEmpty()
                    || $groups[1]->diff($groups[0])->isEmpty();
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
                    return collect()->range((int)$start, (int)$end);
                });
            })
            ->filter(function ($groups) {
                return $groups[0]->intersect($groups[1])->isNotEmpty()
                    || $groups[1]->intersect($groups[0])->isNotEmpty();
            })
            ->count();
    }
}