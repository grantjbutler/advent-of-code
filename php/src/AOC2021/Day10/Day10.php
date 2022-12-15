<?php

namespace AOC2021\Day10;

use AOC\Day;
use AOC\Input;

class Day10 extends Day {
    public function part1(Input $input) {
        $self = $this;

        return $input->lines
            ->map(function($line) use ($self) {
                $stack = collect();
                
                return $line->characters()->mapFirst(function($character) use ($stack, $self) {
                    switch ($character) {
                        case '(':
                        case '[':
                        case '{':
                        case '<':
                            $stack->push($character);
                            break;
                        case ')':
                        case ']':
                        case '}':
                        case '>':
                            $openingCharacter = $stack->pop();
                            if (!$self->matches($character, $openingCharacter)) {
                                return $self->part1Score($character);
                            }
                    }
                }) ?? 0;
            })
            ->sum();
    }

    public function part2(Input $input) {
        $self = $this;

        return $input->lines
            ->map(function($line) use ($self) {
                $stack = collect();
                
                $isValid = $line->characters()->every(function($character) use ($stack, $self) {
                    switch ($character) {
                        case '(':
                        case '[':
                        case '{':
                        case '<':
                            $stack->push($character);
                            return true;
                        case ')':
                        case ']':
                        case '}':
                        case '>':
                            return $self->matches($character, $stack->pop());
                    }
                });

                if (!$isValid) {
                    return false;
                } else {
                    return $stack;
                }
            })
            ->filter()
            ->map(function($stack) use ($self) {
                return $stack->map(fn ($character) => $self->closingCharacter($character))
                    ->reverse()
                    ->reduce(function($total, $character) use ($self) {
                        return ($total * 5) + $self->part2Score($character);
                    }, 0);
            })
            ->sort()
            ->median();
    }

    private function matches($close, $open) {
        switch ($close) {
            case ')': return $open == '(';
            case ']': return $open == '[';
            case '}': return $open == '{';
            case '>': return $open == '<';
        }

        return false;
    }

    private function part1Score($character) {
        return [
            ')' => 3,
            ']' => 57,
            '}' => 1197,
            '>' => 25137
        ][$character];
    }

    private function part2Score($character) {
        return [
            ')' => 1,
            ']' => 2,
            '}' => 3,
            '>' => 4
        ][$character];
    }

    private function closingCharacter($open) {
        switch ($open) {
            case '(': return ')';
            case '[': return ']';
            case '{': return '}';
            case '<': return '>';
        }
    }
}