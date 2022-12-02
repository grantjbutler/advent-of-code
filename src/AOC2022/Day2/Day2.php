<?php

namespace AOC2022\Day2;

use AOC\Day;
use AOC\Input;

enum Move {
    case ROCK;
    case PAPER;
    case SCISSOR;

    static function from(string $value): Move {
        switch ($value) {
            case 'A':
            case 'X':
                return Move::ROCK;
            case 'B':
            case 'Y':
                return Move::PAPER;
            case 'C':
            case 'Z':
                return Move::SCISSOR;
            default:
                throw new \Exception();
        }
    }

    function score(): int {
        switch ($this) {
            case Move::ROCK:
                return 1;
            case Move::PAPER:
                return 2;
            case Move::SCISSOR:
                return 3;
        }
    }

    function result(Move $opponent): Result {
        if ($opponent == $this) {
            return Result::DRAW;
        }

        switch ($opponent) {
            case Move::ROCK:
                if ($this == Move::PAPER) {
                    return Result::WIN;
                } else {
                    return RESULT::LOSS;
                }
            case Move::PAPER:
                if ($this == Move::SCISSOR) {
                    return Result::WIN;
                } else {
                    return RESULT::LOSS;
                }
            case Move::SCISSOR:
                if ($this == Move::ROCK) {
                    return Result::WIN;
                } else {
                    return Result::LOSS;
                }
        }
    }

    function roundScore(Move $opponent): int {
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

    function move(Move $opponent): Move {
        if ($this == Result::DRAW) {
            return $opponent;
        }

        switch ($opponent) {
            case Move::ROCK:
                if ($this == Result::LOSS) {
                    return Move::SCISSOR;
                } else {
                    return Move::PAPER;
                }
            case Move::PAPER:
                if ($this == Result::LOSS) {
                    return Move::ROCK;
                } else {
                    return Move::SCISSOR;
                }
            case Move::SCISSOR:
                if ($this == Result::LOSS) {
                    return Move::PAPER;
                } else {
                    return Move::ROCK;
                }
        }
    }

    function roundScore(Move $opponent): int {
        return $this->move($opponent)->roundScore($opponent);
    }
}

class Day2 extends Day {
    public function part1(Input $input) {
        return $input->lines->map(function ($pair) {
            [$opponent, $me] = explode(' ', $pair);

            return Move::from($me)->roundScore(Move::from($opponent));
        })
        ->sum();
    }

    public function part2(Input $input) {
        return $input->lines->map(function ($pair) {
            [$opponent, $result] = explode(' ', $pair);
            
            return Result::from($result)->roundScore(Move::from($opponent));
        })
        ->sum();
    }
}