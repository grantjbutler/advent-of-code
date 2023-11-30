<?php

namespace AOC2021\Day21;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day21 extends Day {
    public function part1(Input $input) {
        $players = $input->lines
            ->map(fn ($line) => $line->matches('/Player \d starting position: (?<position>\d)/'))
            ->map(fn ($data) => new Player($data['position']));
        
        $game = new GameState($players, Dice::deterministic());

        while (!$game->takeTurn()) {}

        return $game->dice->getTimesRolled() * $game->players->min('score');
    }

    public function part2(Input $input) {

    }
}

class Player {
    public function __construct(public int $position, public int $score = 0) {}

    public function move(int $movement) {
        $this->position = $this->position + $movement;
        while ($this->position > 10) {
            $this->position -= 10;
        }
        $this->score += $this->position;
    }
}

class GameState {
    public function __construct(public Collection $players, public Dice $dice, private int $currentPlayer = 0) {}

    public function getCurrentPlayer(): Player {
        return $this->players[$this->currentPlayer];
    }

    public function takeTurn(): ?Player {
        $movement = $this->dice->roll() + $this->dice->roll() + $this->dice->roll();
        $player = $this->getCurrentPlayer();
        $player->move($movement);

        if ($player->score >= 1000) {
            return $player;
        }

        $this->currentPlayer = ($this->currentPlayer + 1) % $this->players->count();

        return null;
    }
}

class Dice {
    static function deterministic(): Dice {
        return new static(function () {
            static $i = 0;
            return ($i++ % 100) + 1;
        });
    }

    public function __construct(private $generator, private int $timesRolled = 0) {}

    public function roll(): int {
        $this->timesRolled++;
        return ($this->generator)();
    }

    public function getTimesRolled(): int {
        return $this->timesRolled;
    }
}