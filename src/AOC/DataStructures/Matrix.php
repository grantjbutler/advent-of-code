<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;
use AOC\Geometry\Point;
use Ds\Map;

/**
 * @property int $size
 */
class Matrix {
    private Collection $collection;

    public function __construct(Collection $collection) {
        assert($collection->every(fn ($row) => $row->count() == $collection[0]->count()));

        $this->collection = $collection;
    }

    public function __get($name) {
        if ($name == 'size') {
            return $this->collection->count() * $this->collection[0]->count();
        }
    }

    public function get(Point $point): mixed {
        return $this->collection[$point->y][$point->x];
    }

    public function put(Point $point, mixed $value): void {
        assert($this->collection->has($point->y) && $this->collection[$point->y]->has($point->x));

        $this->collection[$point->y][$point->x] = $value;
    }
    
    public function adjacent(Point $point, bool $includeDiagonals = false): Collection {
        $points = collect([
            new Point($point->x, $point->y - 1),
            new Point($point->x, $point->y + 1),
            new Point($point->x - 1, $point->y),
            new Point($point->x + 1, $point->y)
        ]);

        if ($includeDiagonals) {
            $points->push(
                new Point($point->x - 1, $point->y - 1),
                new Point($point->x - 1, $point->y + 1),
                new Point($point->x + 1, $point->y - 1),
                new Point($point->x + 1, $point->y + 1),
            );
        }

        return $points->filter(fn ($point) => $point->y >= 0 && $point->x >= 0 && $point->y < $this->collection->count() && $point->x < $this->collection[$point->y]->count());
    }

    public function map($block): Matrix {
        return new Matrix(
            $this->collection
                ->map->map(fn ($item) => $block($item))
        );
    }

    public function filter($block): Map {
        $map = new Map();

        for ($y = 0; $y < $this->collection->count(); $y++) {
            for ($x = 0; $x < $this->collection[$y]->count(); $x++) {
                $key = new Point($x, $y);
                if ($block($this->collection[$y][$x], $key)) {
                    $map->put($key, $this->collection[$y][$x]);
                }
            }
        }

        return $map;
    }
}