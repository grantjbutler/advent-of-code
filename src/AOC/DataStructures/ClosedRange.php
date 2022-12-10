<?php

namespace AOC\DataStructures;

class ClosedRange {
    function __construct(public int $start, public int $end) {}

    function contains(int|ClosedRange $other) {
        if (is_int($other)) {
            return $this->start <= $other && $this->end >= $other;
        }

        return $this->start <= $other->start && $this->end >= $other->end;
    }

    function overlaps(ClosedRange $other) {
        return ($this->start <= $other->start && $this->end >= $other->start)
            || ($this->start <= $other->end && $this->end >= $other->end);
    }
}