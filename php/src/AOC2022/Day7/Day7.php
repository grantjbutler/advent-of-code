<?php

namespace AOC2022\Day7;

use AOC\DataStructures\TreeNode;
use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

final class FileNode {
    function __construct(public string $name, public bool $isDirectory, public int $size) {}
}

class Day7 extends Day {
    public function part1(Input $input) {
        $root = $this->buildTree($input);
        
        return $this->directories($root)
            ->filter(fn ($node) => $this->totalSize($node) < 100000)
            ->sum(fn ($node) => $node->value()->size);
    }

    public function part2(Input $input) {
        $root = $this->buildTree($input);
        
        $availableSpace = 70000000 - $this->totalSize($root);
        
        return $this->directories($root)
            ->map(fn ($node) => $node->value()->size)
            ->filter(fn ($size) => ($availableSpace + $size) >= 30000000)
            ->min();
    }

    private function buildTree(Input $input): TreeNode {
        $root = new TreeNode(new FileNode('/', true, -1));

        $current = $root;
        $input->lines
            ->each(function($line) use (&$current) {
                if (($matches = $line->matches('/^\$ cd (?<dir>.*)$/'))) {
                    if ($matches['dir'] == '..') {
                        $current = $current->parent();
                    } else if ($matches['dir'] != '/') {
                        $current = $current->children()->first(fn ($child) => $child->value()->name == $matches['dir']);
                    }
                } else if (($matches = $line->matches('/^dir (?<name>.*)$/'))) {
                    $current->addChild(new TreeNode(new FileNode($matches['name'], true, -1)));
                } else if (($matches = $line->matches('/^(?<size>\d+) (?<name>.*)$/'))) {
                    $current->addChild(new TreeNode(new FileNode($matches['name'], false, (int)$matches['size'])));
                }
            });
        
        return $root;
    }

    private function directories(TreeNode $node): Collection {
        return $node->children()
            ->filter(fn ($node) => $node->value()->isDirectory)
            ->reduce(fn ($directories, $node) => $directories->push($node)->concat($this->directories($node)), collect());
    }

    private function totalSize(TreeNode $node): int {
        if ($node->value()->size != -1) {
            return $node->value()->size;
        }

        return tap($this->calculateSize($node), fn ($size) => $node->value()->size = $size);
    }

    private function calculateSize(TreeNode $node): int {
        return $node->children()
            ->sum(fn ($node) => $this->totalSize($node));
    }
}