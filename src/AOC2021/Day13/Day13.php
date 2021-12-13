<?php

namespace AOC2021\Day13;

use AOC\Day;
use AOC\Input;
use AOC\Geometry\Point;
use AOC\DataStructures\Matrix;

class Day13 extends Day {
    public function part1(Input $input) {
        $points = $input->lines
            ->reduce(function ($points, $line) {
                $matches = $line->matches('/(?<x>\d+),(?<y>\d+)/');
                if ($matches) {
                    $points->push(new Point((int)$matches['x'], (int)$matches['y']));
                }

                return $points;
            }, collect());
        
        $instructions = $input->lines
            ->reduce(function ($instructions, $line) {
                $matches = $line->matches('/fold along (?<direction>[xy])=(?<value>\d+)/');
                if ($matches) {
                    $instructions->push($matches);
                }

                return $instructions;
            }, collect());
        
        $matrix = Matrix::fill('.', $points->max('x'), $points->max('y'));

        $points->each(fn ($point) => $matrix->put($point, '#'));

        $instruction = $instructions[0];
        if ($instruction['direction'] == 'x') {
            [$left, $right] = $matrix->splitVertical($instruction['value']);

            $matrix = $left->zip($right)->mapSpread(function ($left, $right) {
                $right->reverse()
                    ->values()
                    ->filter(fn ($item) => $item == '#')
                    ->each(fn ($item, $key) => $left[$key] = '#');
                return $left;
            });
        } else {
            [$above, $below] = $matrix->splitHorizontal($instruction['value']);

            $matrix = $above->zip($below->reverse())->mapSpread(function ($above, $below) {
                $below->filter(fn ($item) => $item == '#')
                    ->each(fn ($item, $key) => $above[$key] = '#');
                return $above;
            });
        }

        return $matrix->flatten()
            ->filter(fn ($item) => $item == '#')
            ->count();
    }

    public function part2(Input $input) {
        $points = $input->lines
            ->reduce(function ($points, $line) {
                $matches = $line->matches('/(?<x>\d+),(?<y>\d+)/');
                if ($matches) {
                    $points->push(new Point((int)$matches['x'], (int)$matches['y']));
                }

                return $points;
            }, collect());
        
        $instructions = $input->lines
            ->reduce(function ($instructions, $line) {
                $matches = $line->matches('/fold along (?<direction>[xy])=(?<value>\d+)/');
                if ($matches) {
                    $instructions->push($matches);
                }

                return $instructions;
            }, collect());
        
        $matrix = Matrix::fill('.', $points->max('x'), $points->max('y'));

        $points->each(fn ($point) => $matrix->put($point, '#'));

        foreach ($instructions as $instruction) {
            if ($instruction['direction'] == 'x') {
                [$left, $right] = $matrix->splitVertical($instruction['value']);

                $matrix = new Matrix($left->zip($right)->mapSpread(function ($left, $right) {
                    $right->reverse()
                        ->values()
                        ->filter(fn ($item) => $item == '#')
                        ->each(fn ($item, $key) => $left[$key] = '#');
                    return $left;
                }));
            } else {
                [$above, $below] = $matrix->splitHorizontal($instruction['value']);

                $matrix = new Matrix($above->zip($below->reverse())->mapSpread(function ($above, $below) {
                    $below->filter(fn ($item) => $item == '#')
                        ->each(fn ($item, $key) => $above[$key] = '#');
                    return $above;
                }));
            }
        }

        return "\n" . $matrix;
    }
}