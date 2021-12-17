<?php

namespace AOC\Geometry;

class Point {
    public function __construct(public int $x, public int $y) {}

    public function distanceTo(Point $point) {
        return new Point(
            abs($this->x - $point->x) + 1,
            abs($this->y - $point->y) + 1
        );
    }

    public function __toString() {
        return "{$this->x},{$this->y}";
    }
}