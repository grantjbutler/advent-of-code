<?php

namespace AOC2022\Day12;

use AOC\Day;
use AOC\Input;
use JMGQ\AStar\AStar;
use AOC\Geometry\Point;
use AOC\DataStructures\Matrix;
use JMGQ\AStar\DomainLogicInterface;
use JMGQ\AStar\Node\NodeIdentifierInterface;

class Node implements NodeIdentifierInterface {
    function __construct(public Point $point) {}

    public function getUniqueNodeId(): string {
        return $this->point->__toString();
    }
}

class DomainLogic implements DomainLogicInterface
{
    function __construct(private Matrix $matrix) {}

    public function getAdjacentNodes(mixed $node): iterable {
        return $this->matrix->adjacent($node);
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
        return "PART 2 DOES NOT RETURN THE CORRECT ANSWER";

        $matrix = new Matrix($input->lines
            ->map->characters());

        $starts = $matrix->indicesOf('a');
        $end = $matrix->indexOf('E');

        dump($starts->count());

        $aStar = new AStar(new DomainLogic($matrix));

        return $starts->map(fn($start) => count($aStar->run($start, $end)))
            ->sorted()
            ->first() - 1;
    }
}