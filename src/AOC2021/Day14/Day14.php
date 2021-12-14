<?php

namespace AOC2021\Day14;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day14 extends Day {
    public function part1(Input $input) {
        $template = $input->lines[0]
            ->characters();

        $mapping = $input->lines
            ->reduce(function ($mapping, $line) {
                $matches = $line->matches('/(?<pair>[A-Z]{2}) -> (?<element>[A-Z])/');
                if ($matches) {
                    $mapping[$matches['pair']] = $matches['element'];
                }

                return $mapping;
            }, collect());
        
        for ($i = 0; $i < 10; $i++) {
            $template = $this->step($template, $mapping);
        }

        $counts = $template->countBy();
        
        return $counts->max() - $counts->min();
    }

    public function part2(Input $input) {
        $template = $input->lines[0]
            ->characters();

        $mapping = $input->lines
            ->reduce(function ($mapping, $line) {
                $matches = $line->matches('/(?<pair>[A-Z]{2}) -> (?<element>[A-Z])/');
                if ($matches) {
                    $mapping[$matches['pair']] = $matches['element'];
                }

                return $mapping;
            }, collect());
        
        $pairCounts = $template->sliding(2)
            ->map->join('')
            ->countBy();
        
        for ($i = 0; $i < 40; $i++) {
            $pairCounts = $this->stepPart2($pairCounts, $mapping);
        }

        $letterCounts = $pairCounts->reduce(function ($counts, $count, $pair) {
            $counts->put($pair[0], $counts->get($pair[0], 0) + $count);
            $counts->put($pair[1], $counts->get($pair[1], 0) + $count);

            return $counts;
        }, collect());

        return round($letterCounts->max() / 2) - round($letterCounts->min() / 2);
    }

    private function step(Collection $template, Collection $mapping) {
        return $template->sliding(2)
            ->map(function($pair) use ($mapping) {
                $key = $pair->join('');
                $insert = $mapping[$key];

                return collect([$insert, $pair->last()]);
            })
            ->flatten()
            ->prepend($template->first());
    }

    private function stepPart2(Collection $pairCounts, Collection $mapping) {
        return $pairCounts->reduce(function($counts, $count, $pair) use ($mapping) {
            $insert = $mapping[$pair];
            
            $a = $pair[0] . $insert;
            $b = $insert . $pair[1];

            $counts->put($a, $counts->get($a, 0) + $count);
            $counts->put($b, $counts->get($b, 0) + $count);

            return $counts;
        }, collect());
    }
}