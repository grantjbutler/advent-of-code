<?php

namespace AOC\Geometry;

class Line {
    public Point $start;
    public Point $end;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function __toString() {
        return "{$this->start} -> {$this->end}";
    }
}