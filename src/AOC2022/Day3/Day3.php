<?php

namespace AOC2022\Day3;

use AOC\Day;
use AOC\Input;

class Day3 extends Day {
    public function part1(Input $input) {
        return $input->lines
            ->map(function($contents) {
                $chunk = $contents->length / 2;
                return [$contents->substr(0, $chunk), $contents->substr($chunk)];
            })
            ->map(function($compartments) {
                return [
                    $compartments[0]->characters()->unique(),
                    $compartments[1]->characters()->unique()
                ];
            })
            ->map(function($compartments) {
                return $compartments[0]->intersect($compartments[1])->values();
            })
            ->flatMap(function($uniques) {
                return $uniques->map(function($item) {
                    $value = ord($item);
                    if ($value >= 97 && $value <= 122) {
                        return $value - ord('a') + 1;
                    } else if ($value >= 65 && $value <= 90) {
                        return $value - ord('A') + 27;
                    }

                    return 0;
                });
            })
            ->sum();
    }

    public function part2(Input $input) {
        return $input->lines
            ->chunk(3)
            ->map(function($group) {
                return $group->map(function($contents) {
                    return $contents->characters()->unique();
                });
            })
            ->map(function($group) {
                return $group->skip(1)->reduce(function($items, $contents) {
                    return $items->intersect($contents)->values();
                }, $group->first());
            })
            ->flatMap(function($uniques) {
                return $uniques->map(function($item) {
                    $value = ord($item);
                    if ($value >= 97 && $value <= 122) {
                        return $value - ord('a') + 1;
                    } else if ($value >= 65 && $value <= 90) {
                        return $value - ord('A') + 27;
                    }

                    return 0;
                });
            })
            ->sum();
    }
}