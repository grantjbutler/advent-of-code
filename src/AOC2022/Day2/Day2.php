<?php

namespace AOC2022\Day2;

use AOC\Day;
use AOC\Input;

enum Opponent: string {
    case ROCK = 'A';
    case PAPER = 'B';
    case SCISSOR = 'C';
}

enum Me: string {
    case ROCK = 'X';
    case PAPER = 'Y';
    case SCISSOR = 'Z';

    function score(): int {
        switch ($this) {
            case Me::ROCK:
                return 1;
            case Me::PAPER:
                return 2;
            case Me::SCISSOR:
                return 3;
        }
    }

    function result(Opponent $opponent): Result {
        switch ($opponent) {
            case Opponent::ROCK:
                if ($this == Me::ROCK) {
                    return Result::DRAW;
                } else if ($this == Me::PAPER) {
                    return Result::WIN;
                } else {
                    return RESULT::LOSS;
                }
            case Opponent::PAPER:
                if ($this == Me::ROCK) {
                    return Result::LOSS;
                } else if ($this == Me::PAPER) {
                    return Result::DRAW;
                } else {
                    return RESULT::WIN;
                }
            case Opponent::SCISSOR:
                if ($this == Me::ROCK) {
                    return Result::WIN;
                } else if ($this == Me::PAPER) {
                    return Result::LOSS;
                } else {
                    return RESULT::DRAW;
                }
        }
    }

    function roundScore(Opponent $opponent): int {
        return $this->score() + $this->result($opponent)->score();
    }
}

enum Result: string {
    case LOSS = 'X';
    case DRAW = 'Y';
    case WIN = 'Z';

    function score(): int {
        switch ($this) {
            case Result::LOSS:
                return 0;
            case Result::DRAW:
                return 3;
            case Result::WIN:
                return 6;
        }
    }

    function move(Opponent $opponent): Me {
        switch ($opponent) {
            case Opponent::ROCK:
                if ($this == Result::LOSS) {
                    return Me::SCISSOR;
                } else if ($this == Result::WIN) {
                    return Me::PAPER;
                } else {
                    return ME::ROCK;
                }
            case Opponent::PAPER:
                if ($this == Result::LOSS) {
                    return Me::ROCK;
                } else if ($this == Result::DRAW) {
                    return Me::PAPER;
                } else {
                    return Me::SCISSOR;
                }
            case Opponent::SCISSOR:
                if ($this == Result::WIN) {
                    return Me::ROCK;
                } else if ($this == Result::LOSS) {
                    return Me::PAPER;
                } else {
                    return ME::SCISSOR;
                }
        }
    }

    function roundScore(Opponent $opponent): int {
        return $this->move($opponent)->roundScore($opponent);
    }
}

class Day2 extends Day {
    public function part1(Input $input) {
        return $input->lines->map(function ($pair) {
            [$opponent, $me] = explode(' ', $pair);

            return Me::from($me)->roundScore(Opponent::from($opponent));
        })
        ->sum();
    }

    public function part2(Input $input) {
        return $input->lines->map(function ($pair) {
            [$opponent, $result] = explode(' ', $pair);
            
            return Result::from($result)->roundScore(Opponent::from($opponent));
        })
        ->sum();
    }
}