<?php

namespace AOC2021\Day18;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Stringable;

class Day18 extends Day {
    public function part1(Input $input) {
        $pairs = $input->lines
            ->map(fn (Stringable $line) => $this->parse($line));
    
        $self = $this;
        $sum = $pairs->reduce(function($sum, $pair) use ($self) {
            if (!$sum) {
                return $pair;
            }

            return $self->add($sum, $pair);
        });

        return $sum->magnitude();
    }

    public function part2(Input $input) {
        $pairs = $input->lines
            ->map(fn (Stringable $line) => $this->parse($line));
        
        $max = 0;
        for ($i = 0; $i < $pairs->count(); $i++) {
            for ($j = 0; $j < $pairs->count(); $j++) {
                if ($i == $j) {
                    continue;
                }

                $lhs = $pairs[$i]->clone();
                $rhs = $pairs[$j]->clone();

                $max = max($max, $this->add($lhs, $rhs)->magnitude());
            }
        }

        return $max;
    }

    // MARK: -

    private function parse(Stringable &$line): Pair {
        assert($this->consume($line, '['));
        
        $left = null;
        if ($line->startsWith('[')) {
            $left = $this->parse($line);
        } else {
            $left = (int)$this->readUntil($line, ',');
        }

        assert($this->consume($line, ','));

        $right = null;
        if ($line->startsWith('[')) {
            $right = $this->parse($line);
        } else {
            $right = (int)$this->readUntil($line, ']');
        }

        assert($this->consume($line, ']'));

        $pair = new Pair($left, $right);

        if ($left instanceof Pair) {
            $left->parent = $pair;
        }

        if ($right instanceof Pair) {
            $right->parent = $pair;
        }

        return $pair;
    }

    private function consume(Stringable &$stringable, string $token): bool {
        $length = strlen($token);
        $data = $stringable->substr(0, $length);
        if ($data != $token) {
            return false;
        }

        $stringable = $stringable->substr($length);
        return true;
    }

    private function readUntil(Stringable &$stringable, string $token): string {
        $position = strpos((string)$stringable, $token);
        assert($position !== false);
        $data = $stringable->substr(0, $position);
        $stringable = $stringable->substr($position);
        return $data;
    }

    // MARK: -

    private function add(Pair $lhs, Pair $rhs): Pair {
        $pair = new Pair($lhs, $rhs);
        
        $lhs->parent = $pair;
        $rhs->parent = $pair;

        while (true) {
            if ($this->attemptExplode($pair, 0)) {
                continue;
            }

            if ($this->attemptSplit($pair)) {
                continue;
            }

            break;
        }

        return $pair;
    }

    private function attemptExplode(Pair $pair, int $depth): bool {
        if ($depth >= 4 && is_int($pair->left) && is_int($pair->right)) {
            $pair->explode();

            return true;
        }
        
        if ($pair->left instanceof Pair) {
            if ($this->attemptExplode($pair->left, $depth + 1)) {
                return true;
            }
        }

        if ($pair->right instanceof Pair) {
            if ($this->attemptExplode($pair->right, $depth + 1)) {
                return true;
            }
        }
        
        return false;
    }

    private function attemptSplit(Pair $pair): bool {
        if ($pair->left instanceof Pair) {
            if ($this->attemptSplit($pair->left)) {
                return true;
            }
        } else if ($pair->left >= 10) {
            $pair->left = new Pair(
                floor($pair->left / 2),
                ceil($pair->left / 2)
            );

            $pair->left->parent = $pair;

            return true;
        }

        if ($pair->right instanceof Pair) {
            if ($this->attemptSplit($pair->right)) {
                return true;
            }
        } else if ($pair->right >= 10) {
            $pair->right = new Pair(
                floor($pair->right / 2),
                ceil($pair->right / 2)
            );

            $pair->right->parent = $pair;

            return true;
        }

        return false;
    }
}

final class Pair {
    public function __construct(
        public Pair | int $left,
        public Pair | int $right
    ) {}

    public Pair | null $parent = null;

    public function __toString(): string {
        return "[{$this->left},{$this->right}]";
    }

    public function clone(): Pair {
        $pair = new Pair(
            ($this->left instanceof Pair) ? $this->left->clone() : $this->left,
            ($this->right instanceof Pair) ? $this->right->clone() : $this->right
        );
        
        if ($pair->left instanceof Pair) {
            $pair->left->parent = $pair;
        }

        if ($pair->right instanceof Pair) {
            $pair->right->parent = $pair;
        }

        return $pair;
    }

    public function explode() {
        assert($this->parent);

        $child = $this;
        $pair = $child->parent;
        
        while ($pair && $pair->left === $child) {
            $child = $pair;
            $pair = $pair->parent;
        }

        if ($pair) {
            if ($pair->left instanceof Pair) {
                $pair = $pair->left;

                while ($pair->right instanceof Pair) {
                    $pair = $pair->right;
                }
    
                $pair->right += $this->left;
            } else {
                $pair->left += $this->left;
            }
        }

        $child = $this;
        $pair = $child->parent;

        while ($pair && $pair->right === $child) {
            $child = $pair;
            $pair = $pair->parent;
        }

        if ($pair) {
            if ($pair->right instanceof Pair) {
                $pair = $pair->right;

                while ($pair->left instanceof Pair) {
                    $pair = $pair->left;
                }
    
                $pair->left += $this->right;
            } else {
                $pair->right += $this->right;
            }
        }

        if ($this->parent->left === $this) {
            $this->parent->left = 0;
        } else {
            $this->parent->right = 0;
        }

        $this->parent = null;
    }

    public function magnitude(): int {
        $left = 0;
        if ($this->left instanceof Pair) {
            $left = $this->left->magnitude();
        } else {
            $left = $this->left;
        }

        $right = 0;
        if ($this->right instanceof Pair) {
            $right = $this->right->magnitude();
        } else {
            $right = $this->right;
        }

        return $left * 3 + $right * 2;
    }
}