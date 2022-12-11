<?php

namespace AOC2022\Day11;

use AOC\Day;
use AOC\Input;
use AOC\Parsing\StringParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Monkey {
    private $operation;
    private int $itemsInspected = 0;

    function __construct(private int $id, private Collection $items, callable $operation, private int $test, private int $trueTarget, private int $falseTarget) {
        $this->operation = $operation;
    }

    function getId(): int {
        return $this->id;
    }

    function inspect(): bool|array {
        if ($this->items->isEmpty()) {
            return false;
        }

        $item = gmp_div_q(($this->operation)($this->items->shift()), "3", 0);
        $target = gmp_mod($item, $this->test) == "0" ? $this->trueTarget : $this->falseTarget;

        $this->itemsInspected++;

        return [$item, $target];
    }

    function addItem($item) {
        $this->items->push($item);
    }

    function getItemsInspected(): int {
        return $this->itemsInspected;
    }
}

function buildOperation($first, $operation, $second): callable {
    return function($old) use ($first, $operation, $second) {
        $value = fn ($value) => $value == "old" ? $old : $value;

        if ($operation == "+") {
            return gmp_add($value($first), $value($second));
        } else if ($operation == "*") {
            return gmp_mul($value($first), $value($second));
        }
    };
}

class Day11 extends Day {
    public function part1(Input $input) {
        $monkeys = $input->explode("\n\n")
            ->mapWithkeys(fn ($text) => with($this->parseMonkey(Str::of($text)), fn ($monkey) => [$monkey->getId() => $monkey]));
        
        for ($i = 0; $i < 20; $i++) {
            $this->runRound($monkeys);
        }

        return $monkeys
            ->map(fn ($monkey) => $monkey->getItemsInspected())
            ->sort(fn ($a, $b) => gmp_cmp($a, $b))
            ->reverse()
            ->take(2)
            ->product();
    }

    public function part2(Input $input) {
        return "DOES NOT COMPLETE IN A REASONABLE AMOUNT OF TIME";

        $monkeys = $input->explode("\n\n")
            ->mapWithkeys(fn ($text) => with($this->parseMonkey(Str::of($text)), fn ($monkey) => [$monkey->getId() => $monkey]));
        
        for ($i = 0; $i < 10000; $i++) {
            $this->runRound($monkeys);
        }

        return $monkeys
            ->map(fn ($monkey) => $monkey->getItemsInspected())
            ->sort(fn ($a, $b) => gmp_cmp($a, $b))
            ->reverse()
            ->take(2)
            ->product();
    }

    private function runRound(Collection $monkeys) {
        $monkeys->each(function($monkey) use ($monkeys) {
            while (($result = $monkey->inspect())) {
                [$item, $target] = $result;
                $monkeys[$target]->addItem($item);
            }
        });
    }

    private function parseMonkey(Stringable $input): Monkey {
        $parser = new StringParser($input);
        $id = $parser->readRegex("/^Monkey (?<id>\d):\\n/")["id"];
        $parser->read("  Starting items: ");
        
        $items = collect();
        
        while (true) {
            $items->push(gmp_init($parser->readRegex("/^(?<item>\d+)/")["item"]));

            if (!$parser->read(", ")) {
                break;
            }
        }

        $parser->read("\n");

        $parser->read("  Operation: new = ");
        $equation = $parser->readRegex("/^(?<first>old|\d+) (?<operation>\*|\+) (?<second>old|\d+)\n/");

        $test = $parser->readRegex("/^  Test: divisible by (?<operand>\d+)\n/")["operand"];

        $trueTarget = (int)$parser->readRegex("/^    If true: throw to monkey (?<target>\d)\\n/")["target"];
        $falseTarget = (int)$parser->readRegex("/^    If false: throw to monkey (?<target>\d)/")["target"];

        return new Monkey(
            $id,
            $items,
            buildOperation($equation['first'], $equation['operation'], $equation['second']),
            $test,
            $trueTarget,
            $falseTarget
        );
    }
}