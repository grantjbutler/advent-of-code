<?php

namespace AOC2022\Day12;

use AOC\Day;
use AOC\Input;
use JMGQ\AStar\AStar;
use AOC\Geometry\Point;
use AOC\DataStructures\Matrix;
use JMGQ\AStar\DomainLogicInterface;

class DomainLogic implements DomainLogicInterface
{
    function __construct(private Matrix $matrix) {}

    public function getAdjacentNodes(mixed $node): iterable {
        return $this->matrix->adjacent($node)
            ->filter(fn ($position) => $this->calculateRealCost($node, $position) <= 1);
    }

    public function calculateRealCost(mixed $node, mixed $adjacent): int {
        $current = $this->matrix->get($node);
        $next = $this->matrix->get($adjacent);

        if ($current == 'S') { $current = 'a'; }

        if ($next == 'S') { $next = 'a'; }
        if ($next == 'E') { $next = 'z'; }

        $score = ord($next) - ord($current);

        if ($score > 1) {
            return 100000;
        } else if ($score == 1) {
            return 0;
        } else {
            return 1;
        }
    }

    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): int {
        return $fromNode->manhattanDistanceTo($toNode);
    }
}

class Day12 extends Day {
    public function part1(Input $input) {
        $matrix = new Matrix($input->lines
            ->map->characters());

        $start = $matrix->indexOf('S');
        $end = $matrix->indexOf('E');

        $aStar = new AStar(new DomainLogic($matrix));
        $solution = $aStar->run($start, $end);
        
        return count($solution) - 1;
    }

    public function part2(Input $input) {
        $matrix = new Matrix($input->lines
            ->map->characters());
        $logic = new DomainLogic($matrix);

        $end = $matrix->indexOf('E');
        $starts = $matrix->indicesOf('a')
            ->sorted(fn ($a, $b) => $logic->calculateEstimatedCost($a, $end) - $logic->calculateEstimatedCost($b, $end));

        $aStar = new AStar($logic);

        $length = [$matrix->count, new Point(1000, 1000)];
        while (!$starts->isEmpty()) {
            $position = $starts->first();
            $starts->remove($position);

            $path = $aStar->run($position, $end);

            foreach ($path as $index => $point) {
                if ($matrix->get($point) != 'a') { continue; }
                $starts->remove($point);
                $length = min($length, count($path) - $index - 1);
            }
        }

        return $length;
    }
}