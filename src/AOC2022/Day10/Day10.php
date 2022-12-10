<?php

namespace AOC2022\Day10;

use AOC\Day;
use AOC\Input;
use AOC\DataStructures\Matrix;
use Illuminate\Support\Collection;
use AOC\DataStructures\ClosedRange;

interface Program {
    function advanceBy(int $count);
    function addX(int $value);
}

class SignalStrengthProgram implements Program {
    private int $cycle = 0;
    private int $x = 1;
    private Collection $callbacks;

    public function __construct() {
        $this->callbacks = collect();
    }

    function on(int $value, callable $callback) {
        $this->callbacks[$value] = $callback;
    }

    function advanceBy(int $count) {
        for ($i = 0; $i < $count; $i++) {
            $this->cycle++;

            if (($callback = $this->callbacks->get($this->cycle))) {
                $callback($this);
            }
        }
    }

    function addX(int $value) {
        $this->x += $value;
    }

    function getX() {
        return $this->x;
    }
}

class DrawingProgram implements Program {
    private int $cycle = 0;
    private int $x = 1;
    private $cycleCallback;

    function __construct(callable $cycleCallback) {
        $this->cycleCallback = $cycleCallback;
    }

    function advanceBy(int $count) {
        for ($i = 0; $i < $count; $i++) {
            $this->cycle++;

            ($this->cycleCallback)($this);
        }
    }

    function addX(int $value) {
        $this->x += $value;
    }

    function getX(): int {
        return $this->x;
    }

    function getCycle(): int {
        return $this->cycle;
    }
}

abstract class Instruction {
    abstract function execute(Program $program);
}

class Noop extends Instruction {
    function execute(Program $program) {
        $program->advanceBy(1);
    }
}

class Add extends Instruction {
    function __construct(private int $value) {}

    function execute(Program $program) {
        $program->advanceBy(2);

        $program->addX($this->value);
    }
}

class Day10 extends Day {
    public function part1(Input $input) {
        $values = collect();
        $program = new SignalStrengthProgram();
        foreach (range(20, 220, 40) as $value) {
            $program->on($value, fn ($program) => $values->push($program->getX() * $value));
        }

        $this->parse($input)
            ->each(fn ($instruction) => $instruction->execute($program));
        
        return $values->sum();
    }

    public function part2(Input $input) {
        $screen = Matrix::fill('.', 40, 6);
        $program = new DrawingProgram(
            fn ($program) =>
                with(
                    new ClosedRange($program->getX() - 1, $program->getX() + 1),
                    fn ($range) => $screen->put($program->getCycle() - 1, $range->contains(($program->getCycle() - 1) % 40) ? '#' : '.')
                )
        );

        $this->parse($input)
            ->each(fn ($instruction) => $instruction->execute($program));
        
        return "\n" . $screen;
    }

    private function parse(Input $input): Collection {
        return $input->lines
            ->map(function($line) {
                if ($line->exactly('noop')) {
                    return new Noop();
                } else if (($matches = $line->matches('/^addx (?<value>\-?\d+)$/'))) {
                    return new Add((int)$matches['value']);
                }
            });
    }
}