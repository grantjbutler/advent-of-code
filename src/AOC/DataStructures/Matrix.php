<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;
use AOC\Geometry\Point;
use AOC\Geometry\Size;
use Ds\Map;

/**
 * @property Size $size
 * @property int $count
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

    public function values(): Collection {
        return $this->collection->flatten();
    }
}