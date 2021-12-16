<?php

namespace AOC2021\Day15;

use AOC\Day;
use AOC\Input;
use BlackScorp\Astar\Grid;
use BlackScorp\Astar\Astar;

class Day15 extends Day {
    public function part1(Input $input) {
        $primitiveMap = $input->lines
            ->map->characters()
            ->map->asIntegers()
            ->all();
        
        $grid = new Grid($primitiveMap);

        $start = $grid->getPoint(0, 0);
        $end = $grid->getPoint(count($primitiveMap[0]) - 1, count($primitiveMap) - 1);

        $astar = new Astar($grid);
        $nodes = collect($astar->search($start, $end));

        return $nodes->last()->getTotalScore();
    }

    public function part2(Input $input) {
        $tile = $input->lines
            ->map->characters()
            ->map->asIntegers();
        
        $map = collect();

        for ($i = 0; $i < 5; $i++) {
            $tile->each(function ($row) use ($map, $i) {
                $mapRow = collect();

                for ($j = 0; $j < 5; $j++) {
                    $row->each(function ($cost) use ($mapRow, $i, $j) {
                        $newCost = $cost + $i + $j;
                        while ($newCost > 9) {
                            $newCost -= 9;
                        }

                        $mapRow->push($newCost);
                    });
                }

                $map->push($mapRow);
            });
        }

        $primitiveMap = $map->all();

        $grid = new Grid($primitiveMap);

        $start = $grid->getPoint(0, 0);
        $end = $grid->getPoint(count($primitiveMap[0]) - 1, count($primitiveMap) - 1);

        $astar = new Astar($grid);
        $nodes = collect($astar->search($start, $end));

        return $nodes->last()->getTotalScore();
    }
}