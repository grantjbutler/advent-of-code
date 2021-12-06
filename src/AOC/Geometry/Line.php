<?php

namespace AOC\Geometry;

class Line {
    public Point $start;
    public Point $end;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function xSteps() {
        return collect()->range($this->start->x, $this->end->x);
    }

    public function ySteps() {
        return collect()->range($this->start->y, $this->end->y);
    }

    public function points() {
        $distance = $this->start->distanceTo($this->end);

        return $this->xSteps()->pad($distance->y, $this->start->x)
            ->zip($this->ySteps()->pad($distance->x, $this->start->y))
            ->mapSpread(fn ($x, $y) => new Point($x, $y));
    }

    public function __toString() {
        return "{$this->start} -> {$this->end}";
    }
}