<?php

namespace AOC2022\Day10;

use AOC\Day;
use AOC\Input;
use AOC\DataStructures\Matrix;
use Illuminate\Support\Collection;
use AOC\DataStructures\ClosedRange;

class Program {
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
        $total = 0;
        $program = new Program(function ($program) use (&$total) {
            if (($program->getCycle() + 20) % 40 != 0) {
                return;
            }

            $total += $program->getX() * $program->getCycle();
        });

        $this->parse($input)
            ->each(fn ($instruction) => $instruction->execute($program));
        
        return $total;
    }

    public function part2(Input $input) {
        $screen = Matrix::fill('.', 40, 6);
        $program = new Program(
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