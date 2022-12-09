<?php

namespace AOC2022\Day9;

use AOC\Day;
use AOC\Geometry\Point;
use AOC\Input;
use Illuminate\Support\Collection;
use Ds\Set;

class Day9 extends Day {
    public function part1(Input $input) {
        return $this->run($input->lines
            ->map(fn ($line) => $line->matches('/^(?<direction>[ULDR]) (?<count>\d+)$/')), 2);
    }

    public function part2(Input $input) {
        return $this->run($input->lines
            ->map(fn ($line) => $line->matches('/^(?<direction>[ULDR]) (?<count>\d+)$/')), 10);
    }

    private function run(Collection $instructions, int $knotCount): int {
        $knots = collect()->range(1, $knotCount)->map(fn () => new Point(0, 0));

        return $instructions->reduce(function ($visitedPoints, $instruction) use ($knots) {
            for ($i = 0; $i < $instruction['count']; $i++) {
                switch ($instruction['direction']) {
                    case "U":
                        $knots[0]->y -= 1;
                        break;
                    case "D":
                        $knots[0]->y += 1;
                        break;
                    case "L":
                        $knots[0]->x -= 1;
                        break;
                    case "R":
                        $knots[0]->x += 1;
                        break;
                }

                $knots->sliding(2)
                    ->each(function($knots) {
                        [$head, $tail] = $knots->values();

                        if (!$tail->equals($head) && !$tail->isAdjacentTo($head)) {
                            $distance = $tail->distanceTo($head);
        
                            if ($distance->x > 1) {
                                $tail->x += ($tail->x > $head->x) ? -1 : 1;
                            }
        
                            if ($distance->y > 1) {
                                $tail->y += ($tail->y > $head->y) ? -1 : 1;
                            }
                        }
                    });

                $visitedPoints->add([$knots->last()->x, $knots->last()->y]);
            }

            return $visitedPoints;
        }, new Set())
        ->count();
    }
}