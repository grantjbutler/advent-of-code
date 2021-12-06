<?php

namespace AOC2021\Day5;

use AOC\Day;
use AOC\Input;
use AOC\Geometry\Line;
use AOC\Geometry\Point;
use Ds\Map;

class Day5 extends Day {
    public function part1(Input $input) {
        return $this->countDangerZones(
            $this->processInput($input, false)
        );
    }

    public function part2(Input $input) {
        return $this->countDangerZones(
            $this->processInput($input, true)
        );
    }

    private function processInput(Input $input, bool $allowDiagonals) {
        return $input->lines
            ->map(function($line) {
                preg_match('/(?<x1>[0-9]+),(?<y1>[0-9]+)\s->\s(?<x2>[0-9]+),(?<y2>[0-9]+)/', $line, $matches);
        
                return new Line(
                    new Point((int)$matches['x1'], (int)$matches['y1']),
                    new Point((int)$matches['x2'], (int)$matches['y2'])
                );
            })
            ->filter(fn ($line) => $allowDiagonals ? true : $line->start->x == $line->end->x || $line->start->y == $line->end->y);
    }

    private function countDangerZones($lines) {
        return $lines->reduce(function($totals, $line) {
            $distance = $line->start->distanceTo($line->end);
            return collect()->range($line->start->x, $line->end->x)->pad($distance->y, $line->start->x)
                ->zip(collect()->range($line->start->y, $line->end->y)->pad($distance->x, $line->start->y))
                ->mapSpread(fn ($x, $y) => new Point($x, $y))
                ->reduce(function($totals, $point) {
                    $totals->put((string)$point, $totals->get((string)$point, 0) + 1);
                    return $totals;
                }, $totals);
        }, collect())
            ->values()
            ->filter(fn ($count) => $count >= 2)
            ->count();
    }
}