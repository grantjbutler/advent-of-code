<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;
use AOC\Geometry\Point;
use AOC\Geometry\Size;
use Ds\Map;

/**
 * @property int $size
 * @property Size $axisSize
 */
class Matrix {
    private Collection $collection;

    public function __construct(Collection $collection) {
        assert($collection->every(fn ($row) => $row->count() == $collection[0]->count()));

        $this->collection = $collection;
    }

    public static function fill(mixed $value, int $width, int $height): Matrix {
        $fill = collect()->range(0, $height)
            ->map(fn () => collect()->range(0, $width)->map(fn () => $value));
        return new static($fill);
    }

    public function __get($name) {
        if ($name == 'size') {
            return $this->collection->count() * $this->collection[0]->count();
        } else if ($name == 'axisSize') {
            return new Size($this->collection[0]->count(), $this->collection->count());
        }
    }

    public function __toString() {
        return $this->collection
            ->map->join('')
            ->join("\n");
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
            $this->collection->map(fn ($row, $y) => $row->map(fn ($item, $x) => $block($item, new Point($x, $y))))
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

    public function values(): Collection {
        return $this->collection->flatten();
    }
}