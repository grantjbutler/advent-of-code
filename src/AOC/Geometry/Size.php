<?php

namespace AOC\Geometry;

class Size {
    public function __construct(public int $width, public int $height) {}

    public function __toString() {
        return "{$this->width},{$this->height}";
    }
}