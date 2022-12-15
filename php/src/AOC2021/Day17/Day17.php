<?php

namespace AOC2021\Day17;

use AOC\Day;
use AOC\Geometry\Point;
use AOC\Geometry\Rect;
use AOC\Geometry\Size;
use AOC\Input;

class Day17 extends Day {
    public function part1(Input $input) {
        $positions = $input->lines[0]->matches('/target area: x=(?<x1>-?\d+)..(?<x2>-?\d+), y=(?<y1>-?\d+)..(?<y2>-?\d+)/');

        $targetRect = new Rect(
            new Point((int)$positions['x1'], (int)$positions['y1']),
            new Point((int)$positions['x2'], (int)$positions['y2'])
        );

        $xs = collect()->range(1, $targetRect->topRight->x);
        $ys = collect()->range(1, abs($targetRect->bottomLeft->y));

        $self = $this;
        return $xs->crossJoin($ys)->reduce(function ($maxY, $velocity) use ($targetRect, $self) {
            $initialVelocity = new Point($velocity[0], $velocity[1]);
            $position = new Point(0, 0);

            $velocity = clone $initialVelocity;

            $steps = collect();
            while ($position->x < $targetRect->topRight->x && $position->y > $targetRect->bottomLeft->y) {
                $position = $self->step($position, $velocity);
                
                $steps->push($position);
                
                if ($targetRect->contains($position)) {
                    $maxY = max($maxY, $steps->max('y'));

                    break;
                }
            }

            return $maxY;
        }, 0);
    }

    public function part2(Input $input) {
        $positions = $input->lines[0]->matches('/target area: x=(?<x1>-?\d+)..(?<x2>-?\d+), y=(?<y1>-?\d+)..(?<y2>-?\d+)/');

        $targetRect = new Rect(
            new Point((int)$positions['x1'], (int)$positions['y1']),
            new Point((int)$positions['x2'], (int)$positions['y2'])
        );

        $xs = collect()->range(1, $targetRect->topRight->x);
        $ys = collect()->range($targetRect->bottomLeft->y, abs($targetRect->bottomLeft->y));

        $self = $this;
        $velocities = $xs->crossJoin($ys)->reduce(function ($velocities, $velocity) use ($targetRect, $self) {
            $initialVelocity = new Point($velocity[0], $velocity[1]);
            $position = new Point(0, 0);

            $velocity = clone $initialVelocity;

            while ($position->x < $targetRect->topRight->x && $position->y > $targetRect->bottomLeft->y) {
                $position = $self->step($position, $velocity);
                
                if ($targetRect->contains($position)) {
                    $velocities->push($initialVelocity);
                    break;
                }
            }

            return $velocities;
        }, collect());

        return $velocities->count();
    }

    private function step(Point $position, Point $velocity): Point {
        $position = clone $position;
        $position->x += $velocity->x;
        $position->y += $velocity->y;

        if ($velocity->x > 0) {
            $velocity->x -= 1;
        } else if ($velocity->x < 0) {
            $velocity->x += 1;
        }

        $velocity->y -= 1;

        return $position;
    }
}