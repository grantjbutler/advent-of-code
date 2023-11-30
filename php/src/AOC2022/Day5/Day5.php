<?php

namespace AOC2022\Day5;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use Illuminate\Support\Str;

class Day5 extends Day {
    public function part1(Input $input) {
        [$initialState, $instructions] = $input->groups;
        $state = $this->parseState($initialState);

        $this->parseInstructions($instructions)
            ->each(function ($instruction) use ($state) {
                for ($i = 0; $i < $instruction['count']; $i++) {
                    $state[$instruction['to'] - 1]->push($state[$instruction['from'] - 1]->pop());
                }
            });
        
        return $state->map(fn ($stack) => $stack->last())
            ->join('');
    }

    public function part2(Input $input) {
        [$initialState, $instructions] = $input->groups;
        $state = $this->parseState($initialState);

        $this->parseInstructions($instructions)
            ->each(function ($instruction) use ($state) {
                $popped = $state[$instruction['from'] - 1]->pop((int)$instruction['count']);
                if (!($popped instanceof Collection)) {
                    $popped = collect([$popped]);
                }
                $popped
                    ->reverse()
                    ->each(fn($crate) => $state[$instruction['to'] - 1]->push($crate));
            });
        
        return $state->map(fn ($stack) => $stack->last())
            ->join('');
    }

    private function parseState(Stringable $state) {
        $lines = $state->explode("\n")
            ->map(fn($str) => Str::of($str))
            ->reverse()
            ->values();
        $stackCount = $lines[0]->explode(' ')->filter()->count();
        
        $stacks = collect()->range(1, $stackCount)->map(fn ($_) => collect());

        $lines->skip(1)->each(function ($line) use ($stacks, $stackCount) {
            for ($i = 0; $i < $stackCount; $i++) {
                $crate = $line->substr($i * 4, 3);
                if ($crate == '   ') { continue; }

                $stacks[$i]->push($crate->substr(1, 1));
            }
        });

        return $stacks;
    }

    private function parseInstructions(Stringable $instructions) {
        return $instructions->explode("\n")
            ->filter()
            ->map(function(string $line) {
                return Str::of($line)
                    ->matches('/^move (?<count>\d+) from (?<from>\d) to (?<to>\d)$/');
            });
    }
}