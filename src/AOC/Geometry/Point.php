<?php

namespace AOC\Geometry;

class Point {
    public int $x;
    public int $y;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

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