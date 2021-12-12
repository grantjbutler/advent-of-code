<?php

namespace AOC2021\Day12;

use AOC\Day;
use AOC\Input;
use AOC\DataStructures\Graph;
use AOC\DataStructures\TreeNode;

class Day12 extends Day {
    public function part1(Input $input) {
        $graph = $input->lines
            ->reduce(function ($graph, $line) {
                $matches = $line->matches('/(?<start>[^\-]+)-(?<end>[^\-]+)/');
                $graph->connect($matches['start'], $matches['end'], $matches['end'] != 'end');
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
                $graph->connect($matches['start'], $matches['end'], $matches['end'] != 'end');
                return $graph;
            }, new Graph());
        
        $root = new TreeNode('');
        $this->traverse($graph, 'start', $root, true);
        return $root->count('end');
    }

    private function traverse(Graph $graph, $node, TreeNode $current, $canVisitSmallCaveTwice = false, int $level = 0) {
        $isSmallCave = strtolower($node) === $node;
        if ($isSmallCave && $current->hasAnyParent($node)) {
            if ($canVisitSmallCaveTwice) {
                $canVisitSmallCaveTwice = false;
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
            ->each(fn ($node) => $this->traverse($graph, $node, $newNode, $canVisitSmallCaveTwice, $level + 1));
    }
}