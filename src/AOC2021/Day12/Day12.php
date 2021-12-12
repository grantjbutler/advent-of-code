<?php

namespace AOC2021\Day12;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day12 extends Day {
    public function part1(Input $input) {
        $graph = $input->lines
            ->reduce(function ($graph, $line) {
                $matches = $line->matches('/(?<start>[^\-]+)-(?<end>[^\-]+)/');
                $graph->connect($matches['start'], $matches['end']);
                return $graph;
            }, new Graph());
        
        $root = new TreeNode('');
        $this->traverse($graph, 'start', $root);
        return $root->count('end');
    }

    public function part2(Input $input) {
        $graph = $input->lines
            ->reduce(function ($graph, $line) {
                $matches = $line->matches('/(?<start>[^\-]+)-(?<end>[^\-]+)/');
                $graph->connect($matches['start'], $matches['end']);
                return $graph;
            }, new Graph());
        
        $root = new TreeNode('');
        $this->traverse($graph, 'start', $root, true);
        return $root->count('end');
    }

    private function traverse(Graph $graph, $node, TreeNode $current, $visitSmallCaveTwice = false, int $level = 0) {
        $isSmallCave = strtolower($node) === $node;
        if ($isSmallCave && $current->hasAnyParent($node)) {
            if ($visitSmallCaveTwice) {
                $visitSmallCaveTwice = false;
            } else {
                return;
            }
        }

        $newNode = new TreeNode($node);
        $current->addChild($newNode);
        if ($node == 'end') {
            return;
        }

        $graph->connections($node)
            ->filter(fn ($node) => $node != 'start')
            ->each(fn ($node) => $this->traverse($graph, $node, $newNode, $visitSmallCaveTwice, $level + 1));
    }
}

class Graph {
    private $connections;

    public function __construct() {
        $this->connections = collect();
    }

    public function connect($a, $b) {
        $this->connections->put($a, $this->connections->get($a, collect())->push($b));

        if ($b != 'end') {
            $this->connections->put($b, $this->connections->get($b, collect())->push($a));
        }
    }

    public function connections($node) {
        return $this->connections[$node];
    }
}

class TreeNode {
    private mixed $value;
    private Collection $children;
    private TreeNode | null $parent = null;

    public function __construct(mixed $value) {
        $this->value = $value;
        $this->children = collect();
    }

    public function addChild(TreeNode $node) {
        $this->children->push($node);
        $node->parent = $this;
    }

    public function hasAnyParent(mixed $value) {
        $node = $this;

        while ($node) {
            if ($node->value == $value) {
                return true;
            }
            $node = $node->parent;
        }

        return false;
    }

    public function count($value) {
        $number = $this->value == $value ? 1 : 0;

        return $number + $this->children
            ->map->count($value)
            ->sum();
    }
}