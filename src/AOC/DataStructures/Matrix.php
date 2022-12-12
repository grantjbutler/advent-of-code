<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;
use AOC\Geometry\Point;
use AOC\Geometry\Size;
use Ds\Map;
use Ds\Set;

/**
 * @property Size $size
 * @property int $count
 */
class Matrix {
    private Collection $collection;

    public function __clone() {
        $this->collection = clone $this->collection;
    }

    public function __construct(Collection $collection) {
        assert($collection->every(fn ($row) => $row->count() == $collection[0]->count()));

        $this->collection = $collection;
    }

    public static function fill(mixed $value, int $width, int $height): Matrix {
        $fill = collect()->range(0, $height - 1)
            ->map(fn () => collect()->range(0, $width - 1)->map(fn () => $value));
        return new static($fill);
    }

    public function __get($name) {
        if ($name == 'size') {
            return new Size($this->collection[0]->count(), $this->collection->count());
        } else if ($name == 'count') {
            return $this->collection[0]->count() * $this->collection->count();
        }
    }

    public function __toString() {
        return $this->collection
            ->map->join('')
            ->join("\n");
    }

    public function get(int|Point $index): mixed {
        if (is_int($index)) {
            $index = new Point($index % $this->collection[0]->count(), floor($index / $this->collection[0]->count()));
        }
        
        return $this->collection[$index->y][$index->x];
    }

    public function put(int|Point $index, mixed $value): void {
        if (is_int($index)) {
            $index = new Point($index % $this->collection[0]->count(), floor($index / $this->collection[0]->count()));
        }
        
        assert($this->collection->has($index->y) && $this->collection[$index->y]->has($index->x));

        $this->collection[$index->y][$index->x] = $value;
    }
    
    public function adjacent(Point $point, bool $includeDiagonals = false): Collection {
        $points = [
            new Point($point->x, $point->y - 1),
            new Point($point->x, $point->y + 1),
            new Point($point->x - 1, $point->y),
            new Point($point->x + 1, $point->y)
        ];

        if ($includeDiagonals) {
            array_push($points,
                new Point($point->x - 1, $point->y - 1),
                new Point($point->x - 1, $point->y + 1),
                new Point($point->x + 1, $point->y - 1),
                new Point($point->x + 1, $point->y + 1),
            );
        }

        return collect(array_filter($points, fn ($point) => $point->y >= 0 && $point->x >= 0 && $point->y < $this->collection->count() && $point->x < $this->collection[$point->y]->count()));
    }

    public function each($block) {
        $this->collection->each(
            fn ($row, $y) => $row->each(fn ($item, $x) => $block($item, new Point($x, $y)))
        );
    }

    public function map($block): Matrix {
        return new Matrix(
            $this->collection->map(fn ($row, $y) => $row->map(fn ($item, $x) => $block($item, new Point($x, $y))))
        );
    }

    public function filter($block): Map {
        $map = new Map();

        $this->each(function ($item, $location) use ($block, $map) {
            if ($block($item, $location)) {
                $map->put($location, $item);
            }
        });

        return $map;
    }

    public function reduce($block, $initial = null) {
        $value = $initial;
        $this->each(function ($item, $location) use (&$value, $block) {
            $value = $block($value, $item, $location);
        });
        return $value;
    }

    public function splitHorizontal(int $y): array {
        return [
            $this->collection->slice(0, $y),
            $this->collection->slice($y + 1)
        ];
    }

    public function splitVertical(int $x): array {
        return [
            $this->collection->map->slice(0, $x),
            $this->collection->map->slice($x + 1)
        ];
    }

    public function min(null | callable $callback = null): mixed {
        if (!$callback) {
            return $this->collection->flatten()->min();
        }

        return $this->reduce(function ($result, $item, $location) use ($callback) {
            $value = $callback($item, $location);
            return is_null($result) || $value < $result ? $value : $result;
        });
    }

    public function max(null | callable $callback = null): mixed {
        if (!$callback) {
            return $this->collection->flatten()->max();
        }

        return $this->reduce(function ($result, $item, $location) use ($callback) {
            $value = $callback($item, $location);
            return is_null($result) || $value > $result ? $value : $result;
        });
    }

    public function indexOf(mixed $value): Point|null {
        for ($y = 0; $y < $this->collection->count(); $y++) {
            for ($x = 0; $x < $this->collection[$y]->count(); $x++) {
                if ($this->collection[$y][$x] == $value) {
                    return new Point($x, $y);
                }
            }
        }

        return null;
    }

    public function indicesOf(mixed $value): Set {
        return $this->filter(fn ($item) => $item == $value)
            ->keys();
    }

    public function values(): Collection {
        return $this->collection->flatten();
    }
}