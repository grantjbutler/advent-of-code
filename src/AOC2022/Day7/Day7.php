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
        
        return $this->directories($root)
            ->filter(fn ($node) => $this->totalSize($node) < 100000)
            ->sum(fn ($node) => $node->value()->size);
    }

    public function part2(Input $input) {
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
        
        $availableSpace = 70000000 - $this->totalSize($root);
        
        return $this->directories($root)
            ->reduce(function ($directories, $directory) {
                $directories->put($directory->value()->name, $directory->value()->size);
                return $directories;
            }, collect())
            ->filter(fn ($size) => ($availableSpace + $size) >= 30000000)
            ->sort()
            ->first();
    }

    private function directories(TreeNode $node): Collection {
        $self = $this;
        return $node->children()->reduce(function ($directories, $node) use ($self) {
            if ($node->value()->isDirectory) {
                $directories->push($node);

                return $directories->concat($self->directories($node));
            }
            
            return $directories;
        }, collect());
    }

    private function totalSize(TreeNode $node): int {
        if ($node->value()->size != -1) {
            return $node->value()->size;
        }

        $self = $this;
        $size = $node->children()->reduce(function ($total, $node) use ($self) {
            if ($node->value()->isDirectory) {
                return $total + $self->totalSize($node);
            } else {
                return $total + $node->value()->size;
            }
        }, 0);
        $node->value()->size = $size;
        return $size;
    }
}