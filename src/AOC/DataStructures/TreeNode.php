<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;

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

    public function hasAnyParent(mixed $value): bool {
        $node = $this;

        while ($node) {
            if ($node->value == $value) {
                return true;
            }
            $node = $node->parent;
        }

        return false;
    }

    public function count(mixed $value): int {
        $number = $this->value == $value ? 1 : 0;

        return $number + $this->children
            ->map->count($value)
            ->sum();
    }
}