<?php

namespace AOC2022\Day14;

use AOC\Day;
use AOC\Geometry\Line;
use AOC\Geometry\Point;
use AOC\Input;
use AOC\Parsing\StringParser;
use Ds\Set;
use Illuminate\Support\Collection;

class Day14 extends Day {
    public function part1(Input $input) {
        $rockPaths = $input->lines->map(fn ($line) => $this->parse($line));
        $lowestPoint = $rockPaths->max(fn ($path) => $path->rect()->bottomLeft->y);

        $sand = new Set();

        $i = 0;
        while (true) {
            $grain = new Point(500, 0);

            do {
                $nextPosition = tap(clone $grain, function ($grain) {
                    $grain->y += 1;
                });

                if (!$this->canPlace($nextPosition, $rockPaths, $sand)) {
                    $nextPosition->x = $grain->x - 1;

                    if (!$this->canPlace($nextPosition, $rockPaths, $sand)) {
                        $nextPosition->x = $grain->x + 1;

                        if (!$this->canPlace($nextPosition, $rockPaths, $sand)) {
                            $sand->add($grain);
                            break;
                        } else {
                            // dump("can place down right");
                        }
                    } else {
                        // dump("can place down left");
                    }
                } else {
                    // dump("can place down");
                }

                // dump($grain);

                $grain = $nextPosition;

                if ($grain->y > $lowestPoint) {
                    return $sand->count();
                }
            } while (true);

            // if ($i == 2) { die(); }

            $i++;
        }
    }

    public function part2(Input $input) {

    }

    private function parse(string $line): Path {
        $parser = new StringParser($line);
        
        $point = $parser->readRegex('/(?<x>\d+),(?<y>\d+)/');
        $point = new Point($point['x'], $point['y']);

        $path = new Path($point);

        while (true) {
            if (!$parser->read(' -> ')) {
                break;
            }

            if (($point = $parser->readRegex('/(?<x>\d+),(?<y>\d+)/')) === false) {
                break;
            }

            $path->lineTo(new Point($point['x'], $point['y']));
        }

        return $path;
    }

    private function canPlace(Point $point, Collection $rockPaths, Set $sand) {
        if ($rockPaths->contains(fn ($path) => $path->contains($point))) {
            // dump("intersects with rock path");

            return false;
        }

        if ($sand->contains($point)) {
            // dump("already sand at this location");

            return false;
        }

        return true;
    }
}

final class Path {
    private Collection $lines;

    function __construct(private Point $position) {
        $this->lines = collect();
    }

    function lineTo(Point $point) {
        $this->lines->push(new Line($this->position, $point));
        $this->position = $point;
    }

    function rect(): Rect {
        return $this->lines->reduce(function ($rect, $line) {
            return tap($rect, function ($rect) use ($line) {
                $rect->topRight->x = max($rect->topRight->x, $line->start->x, $line->end->x);
                $rect->topRight->y = min($rect->topRight->y, $line->start->y, $line->end->y);

                $rect->bottomLeft->x = min($rect->bottomLeft->x, $line->start->x, $line->end->x);
                $rect->bottomLeft->y = max($rect->bottomLeft->y, $line->start->y, $line->end->y);
            });
        }, new Rect(new Point(0, 0), new Point(0, 0)));
    }

    function contains(Point $point): bool {
        return $this->lines->contains(fn ($line) => $this->lineContains($line, $point));
    }

    private function lineContains(Line $line, Point $point): bool {
        return $line->points()->contains($point);


        return $point->x >= $line->start->x && $point->x <= $line->end->x
            && $point->y <= $line->start->y && $point->y >= $line->end->y;
    }
}

// Modified because this rect needs y to get bigger as it goes down.
class Rect {
    public function __construct(public Point $bottomLeft, public Point $topRight) {}

    public function contains(Point $point): bool {
        return $point->x >= $this->bottomLeft->x && $point->x <= $this->topRight->x
            && $point->y <= $this->bottomLeft->y && $point->y >= $this->topRight->y;
    }

    public function __toString(): string {
        return "{bottomLeft=" . $this->bottomLeft . ",topRight=" . $this->topRight . "}";
    }
}