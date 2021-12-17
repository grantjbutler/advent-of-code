<?php

namespace AOC\Geometry;

class Rect {
    public function __construct(public Point $bottomLeft, public Point $topRight) {}

    public function contains(Point $point): bool {
        return $point->x >= $this->bottomLeft->x && $point->x <= $this->topRight->x
            && $point->y >= $this->bottomLeft->y && $point->y <= $this->topRight->y;
    }

    public function __toString(): string {
        return "{bottomLeft=" . $this->bottomLeft . ",topRight=" . $this->topRight . "}";
    }
}