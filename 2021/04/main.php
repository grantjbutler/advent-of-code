<?php

class BingoNumber {
    public int $number;
    public bool $marked = false;

    function __construct($number) {
        $this->number = $number;
    }
}

class BingoBoard {
    public array $numbers;

    function __construct($numbers) {
        $this->numbers = array_map(function ($number) { return new BingoNumber($number); }, $numbers);
    }

    function mark($number) {
        foreach ($this->numbers as $boardNumber) {
            if ($boardNumber->number == $number) {
                $boardNumber->marked = true;
                break;
            }
        }
    }

    function isWinner() {
        for ($x = 0; $x < 5; $x++) {
            for ($y = 0; $y < 5; $y++) {
                if (!$this->numbers[($y * 5) + $x]->marked) {
                    break;
                }

                if ($y == 4) {
                    return true;
                }
            }
        }

        for ($y = 0; $y < 5; $y++) {
            for ($x = 0; $x < 5; $x++) {
                if (!$this->numbers[($y * 5) + $x]->marked) {
                    break;
                }

                if ($x == 4) {
                    return true;
                }
            }
        }

        return false;
    }

    function calculateScore($number) {
        return $number * array_reduce($this->numbers, function($total, $number) {
            if ($number->marked) {
                return $total;
            }

            return $total + $number->number;
        }, 0);
    }

    function __toString() {
        $numbers = array_map(function($number) {
            $string = $number->marked ? '-1' : (string)$number->number;
            if (strlen($string) == 1) {
                $string = ' ' . $string;
            }
            return $string;
        }, $this->numbers);

        $string = '';
        for ($i = 0; $i < 5; $i++) {
            $line = '';
            for ($j = 0; $j < 5; $j++) {
                $line .= $numbers[(5 * $i) +  $j] . ' ';
            }

            $string .= rtrim($line) . "\n";
        }
        $string .= "\n";
        $string .= "Marked Numbers:\n";
        $string .= implode(
            ", ",
            array_map(
                function($number) { return $number->number; },
                array_filter($this->numbers, function($number) { return $number->marked; })
            )
        ) . "\n";

        $string .= "Unmarked Numbers:\n";
        $string .= implode(
            ", ",
            array_map(
                function($number) { return $number->number; },
                array_filter($this->numbers, function($number) { return !$number->marked; })
            )
        ) . "\n";

        return $string;
    }
}

$input = file('input.txt');
$numbersToCall = explode(',', trim(array_shift($input)));
array_shift($input);

$boards = [];
$numbers = [];
$boardToLog = null;
foreach ($input as $line) {
    $line = trim($line);
    if (strlen($line) == 0) {
        $board = new BingoBoard($numbers);
        if ($numbers == [78, 80, 98, 62, 87, 90, 53, 91, 81, 23, 46, 15,  4, 63, 74, 30,  6, 47, 64, 44, 12, 45, 95, 68, 99,]) {
            $boardToLog = $board;
        }

        array_push($boards, $board);
        $numbers = [];
    } else {
        foreach (explode(' ', $line) as $entry) {
            $entry = trim($entry);
            if (strlen($entry) == 0) {
                continue;
            } else {
                array_push($numbers, (int)$entry);
            }
        }
    }
}

if (count($numbers) > 0) {
    array_push($boards, new BingoBoard($numbers));
}

$foundWinningBoard = false;
foreach ($numbersToCall as $number) {
    echo "Drawing {$number}\n";

    foreach($boards as $board) {
        $board->mark($number);
    }

    $count = count($boards);
    echo "checking {$count} boards\n";
    for($i = 0; $i < $count; $i++) {
        $board = $boards[$i];

        if ($board->isWinner()) {
            if (!$foundWinningBoard) {
                $score = $board->calculateScore($number);
                echo "Winning board has a score of {$score}\n";
                $foundWinningBoard = true;
            }

            if ($count == 1) {
                $score = $board->calculateScore($number);
                echo "Losing board has a score of {$score}\n";
                $foundLosingBoard = true;
                exit;
            }

            array_splice($boards, $i, 1);
            $i--;
            $count--;
        }
    }
}