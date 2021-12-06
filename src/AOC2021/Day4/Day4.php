<?php

namespace AOC2021\Day4;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;

class Day4 extends Day {
    public function part1(Input $input) {
        [$numbers, $boards] = $this->processInput($input);
        
        return $numbers->mapFirst(function($number) use ($boards) {
            $boards->each->mark($number);

            return $boards->mapFirst(function ($board) use ($number) {
                if ($board->isWinner()) {
                    return $board->calculateScore($number);
                }

                return null;
            });
        });
    }

    public function part2(Input $input) {
        [$numbers, $boards] = $this->processInput($input);

        foreach ($numbers as $number) {
            $boards->each->mark($number);

            $count = count($boards);
            for($i = 0; $i < $count; $i++) {
                $board = $boards[$i];

                if ($board->isWinner()) {
                    if ($count == 1) {
                        return $board->calculateScore($number);
                    }

                    $boards->splice($i, 1);
                    $i--;
                    $count--;
                }
            }
        }
    }

    public function processInput(Input $input) {
        $lines = $input->lines;
        $numbers = collect(explode(',', $lines->shift()));

        $boards = $lines->chunk(6)
            ->map(function($rows) {
                return $rows->skip(1)
                    ->flatMap(function($row) {
                        preg_match_all('/\d+/', $row, $matches);
                        return collect($matches[0])->map(fn ($num) => (int)$num);
                    });
            })
            ->mapInto(BingoBoard::class);
        
        return [$numbers, $boards];
    }
}

class BingoNumber {
    public int $number;
    public bool $marked = false;

    function __construct($number) {
        $this->number = $number;
    }
}

class BingoBoard {
    public Collection $numbers;

    function __construct(Collection $numbers) {
        $this->numbers = $numbers->map(fn($number) => new BingoNumber($number));
    }

    function mark($number) {
        $this->numbers->where('number', $number)
            ->each(function($number) {
                $number->marked = true;
            });
    }

    function isWinner() {
        $hasColumnWin = $this->numbers
            ->columns(5)
            ->contains(fn ($column) => $column->every(fn ($num) => $num->marked));
        if ($hasColumnWin) {
            return true;
        }

        $hasRowWin = $this->numbers
            ->rows(5)
            ->contains(fn ($row) => $row->every(fn ($num) => $num->marked));
        if ($hasRowWin) {
            return true;
        }

        return false;
    }

    function calculateScore($number) {
        return $number * $this->numbers->reduce(function($total, $number) {
            if ($number->marked) {
                return $total;
            }

            return $total + $number->number;
        }, 0);
    }

    function __toString() {
        $numbers = $this->numbers->map(function($number) {
            $string = $number->marked ? '-1' : (string)$number->number;
            if (strlen($string) == 1) {
                $string = ' ' . $string;
            }
            return $string;
        });

        $string = $numbers->rows()
            ->map(fn ($row) => $row->join(' '))
            ->join("\n");

        $string .= "\n";
        $string .= "Marked Numbers:\n";
        $string .= $this->numbers
            ->filter->marked
            ->map->number
            ->join(', ') . "\n";

        $string .= "Unmarked Numbers:\n";
        $string .= $this->numbers
            ->filter(fn ($number) => !$number->marked)
            ->map->number
            ->join(', ') . "\n";

        return $string;
    }
}