<?php

namespace AOC2022\Day8;

use AOC\DataStructures\Matrix;
use AOC\Geometry\Point;
use AOC\Day;
use AOC\Input;

class Day8 extends Day {
    public function part1(Input $input) {
        $matrix = new Matrix($input->lines->characters()->map->asIntegers());
        $self = $this;

        return $matrix
            ->reduce(function ($count, $height, $location) use ($matrix, $self) {
                if ($location->x == 0 || $location->y == 0 || $location->x == $matrix->axisSize->width - 1 || $location->y == $matrix->axisSize->height - 1) {
                    return $count + 1;
                }

                if ($self->hasLineOfSightToTopEdge($matrix, $location)) {
                    return $count + 1;
                }

                if ($self->hasLineOfSightToBottomEdge($matrix, $location)) {
                    return $count + 1;
                }

                if ($self->hasLineOfSightToLeftEdge($matrix, $location)) {
                    return $count + 1;
                }

                if ($self->hasLineOfSightToRightEdge($matrix, $location)) {
                    return $count + 1;
                }

                return $count;
            }, 0);
    }

    public function part2(Input $input) {
        $matrix = new Matrix($input->lines->characters()->map->asIntegers());
        $self = $this;

        return $matrix
            ->max(function ($height, $location) use ($matrix, $self) {
                return $self->treesVisibleToTopEdge($matrix, $location)
                    * $self->treesVisibleToBottomEdge($matrix, $location)
                    * $self->treesVisibleToLeftEdge($matrix, $location)
                    * $self->treesVisibleToRightEdge($matrix, $location);
            });
    }

    private function hasLineOfSightToTopEdge(Matrix $matrix, Point $location): bool {
        $height = $matrix->get($location);
        
        for ($x = $location->x - 1; $x >= 0; $x--) {
            if ($matrix->get(new Point($x, $location->y)) >= $height) {
                return false;
            }
        }

        return true;
    }

    private function hasLineOfSightToBottomEdge(Matrix $matrix, Point $location): bool {
        $height = $matrix->get($location);
        
        for ($x = $location->x + 1; $x < $matrix->axisSize->width; $x++) {
            if ($matrix->get(new Point($x, $location->y)) >= $height) {
                return false;
            }
        }

        return true;
    }

    private function hasLineOfSightToLeftEdge(Matrix $matrix, Point $location): bool {
        $height = $matrix->get($location);
        
        for ($y = $location->y - 1; $y >= 0; $y--) {
            if ($matrix->get(new Point($location->x, $y)) >= $height) {
                return false;
            }
        }

        return true;
    }

    private function hasLineOfSightToRightEdge(Matrix $matrix, Point $location): bool {
        $height = $matrix->get($location);
        
        for ($y = $location->y + 1; $y < $matrix->axisSize->height; $y++) {
            if ($matrix->get(new Point($location->x, $y)) >= $height) {
                return false;
            }
        }

        return true;
    }

    // --

    private function treesVisibleToTopEdge(Matrix $matrix, Point $location): int {
        $height = $matrix->get($location);
        $count = 0;

        for ($x = $location->x - 1; $x >= 0; $x--) {
            $count++;

            if ($matrix->get(new Point($x, $location->y)) >= $height) {
                break;
            }
        }

        return $count;
    }

    private function treesVisibleToBottomEdge(Matrix $matrix, Point $location): int {
        $height = $matrix->get($location);
        $count = 0;

        for ($x = $location->x + 1; $x < $matrix->axisSize->width; $x++) {
            $count++;

            if ($matrix->get(new Point($x, $location->y)) >= $height) {
                break;
            }
        }

        return $count;
    }

    private function treesVisibleToLeftEdge(Matrix $matrix, Point $location): int {
        $height = $matrix->get($location);
        $count = 0;

        for ($y = $location->y - 1; $y >= 0; $y--) {
            $count++;

            if ($matrix->get(new Point($location->x, $y)) >= $height) {
                break;
            }
        }

        return $count;
    }

    private function treesVisibleToRightEdge(Matrix $matrix, Point $location): int {
        $height = $matrix->get($location);
        $count = 0;

        for ($y = $location->y + 1; $y < $matrix->axisSize->height; $y++) {
            $count++;
            
            if ($matrix->get(new Point($location->x, $y)) >= $height) {
                break;
            }
        }

        return $count;
    }
}