<?php

namespace AOC\Geometry;

use Ds\Hashable;

class Point implements Hashable {
    public function __construct(public int $x, public int $y) {}

    public function distanceTo(Point $point) {
        return new Point(
            abs($this->x - $point->x) + 1,
            abs($this->y - $point->y) + 1
        );
    }

    public function manhattanDistanceTo(Point $point) {
        $deltaX = abs($this->x - $point->x);
        $deltaY = abs($this->y - $point->y);
        return $deltaX + $deltaY;
    }

    public function isAdjacentTo(Point $point) {
        return abs($this->x - $point->x) <= 1
            && abs($this->y - $point->y) <= 1;
    }

    public function equals($point): bool {
        return $this->x == $point->x
            && $this->y == $point->y;
    }

    public function hash() {
        return $this->x ^ $this->y;
    }

    public function __toString() {
        return "{$this->x},{$this->y}";
    }
}