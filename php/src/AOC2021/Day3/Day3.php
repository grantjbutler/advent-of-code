<?php

namespace AOC2021\Day3;

use AOC\Day;
use AOC\Input;

class Day3 extends Day {
    public function part1(Input $input) {
        $bits = $input->lines
            ->characters();
        
        $rates = $bits[0]->keys()
            ->reduce(function($rates, $index) use ($bits) {
                $counts = $bits->pluck($index)
                    ->countBy();
                $rates['gamma']->push($counts->maxKey());
                $rates['epsilon']->push($counts->minKey());
                return $rates;
            }, ['gamma' => collect(), 'epsilon' => collect()]);
        
        $gamma = bindec($rates['gamma']->join(''));
        $epsilon = bindec($rates['epsilon']->join(''));

        return $gamma * $epsilon;
    }

    public function part2(Input $input) {
        $bits = $input->lines
            ->characters();
        
        $self = $this;
        $rates = $bits[0]->keys()
            ->reduce(function($bits, $index) use ($self) {
                if ($bits['o2']->count() > 1) {
                    $bits['o2'] =  $self->filterInput($bits['o2'], $index, function ($lhs, $rhs) { return $lhs->count() > $rhs->count(); });
                }

                if ($bits['co2']->count() > 1) {
                    $bits['co2'] =  $self->filterInput($bits['co2'], $index, function ($lhs, $rhs) { return $lhs->count() <= $rhs->count(); });
                }

                return $bits;
            }, ['o2' => $bits, 'co2' => $bits]);
        
        $o2 = bindec($rates['o2'][0]->join(''));
        $co2 = bindec($rates['co2'][0]->join(''));

        return $o2 * $co2;
    }

    private function filterInput($bits, $index, $comparator) {
        $entries = $bits->reduce(function($counts, $entry) use ($index) {
            $counts[$entry[$index]]->push($entry);
            return $counts;
        }, ['0' => collect(), '1' => collect()]);

        if ($comparator($entries['0'], $entries['1'])) {
            return $entries['0'];
        } else {
            return $entries['1'];
        }
    }
}