<?php

namespace AOC2021\Day8;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Day8 extends Day {
    public function part1(Input $input) {
        $digits = $input->lines
            ->map(function($line) {
                return Str::of($line->explode('|')[1])
                    ->trim()
                    ->explode(' ');
            })
            ->map(function($digits) {
                return $digits->countBy(function($digit) {
                    switch (strlen($digit)) {
                        case 2:
                            return '1';
                        case 4:
                            return '4';
                        case 3:
                            return '7';
                        case 7:
                            return '8';
                        default:
                            return '5';
                    }
                });
            })
            ->reduce(function ($counts, $digits) {
                $digits->each(function($count, $digit) use ($counts) {
                    $counts->put($digit, $count + $counts->get($digit, 0));
                });

                return $counts;
            }, collect());
        
        return $digits['1'] + $digits['4'] + $digits['7'] + $digits['8'];
    }

    public function part2(Input $input) {
        $self = $this;

        return $input->lines
            ->map(function($line) use ($self) {
                [$sequence, $digits] = $line->explode('|')
                    ->map(function($component) {
                        return Str::of($component)->trim()
                            ->explode(' ')
                            ->map(function ($item) {
                                return Str::of($item);
                            });
                    });
                
                $mapping = $self->generateMapping($sequence);
                
                return $self->number($digits, $mapping);
            })
            ->sum();
    }

    private function generateMapping($sequence) {
        $mapping = [
            'a' => '',
            'b' => '',
            'c' => '',
            'd' => '',
            'e' => '',
            'f' => '',
            'g' => '',
        ];
        
        [$easyDigits, $hardDigits] = $sequence->partition(function($value) {
            switch ($value->length()) {
                case 2:
                case 4:
                case 3:
                case 7:
                    return true;
                default:
                    return false;
            }
        });

        $easyDigits = $easyDigits->mapWithKeys(function($value) {
            switch ($value->length()) {
                case 2:
                    return ['1' => $value];
                case 4:
                    return ['4' => $value];
                case 3:
                    return ['7' => $value];
                case 7:
                    return ['8' => $value];
            }
        });

        $mapping['a'] = (string)$easyDigits['7']->trim($easyDigits['1']);

        $bottomAndLeft = $hardDigits->map(function($digit) use ($easyDigits, $mapping) {
            return $digit->replaceMatches('/[' . $easyDigits['4'] . $mapping['a'] . ']/', '');
        });

        $mapping['g'] = (string)$bottomAndLeft
            ->first(function($digit) {
                return $digit->length() == 1;
            });
        
        $mapping['e'] = (string)$bottomAndLeft->map(function($digit) use ($mapping) {
            return $digit->replaceMatches('/[' . $mapping['g'] . ']/', '');
        })
        ->first(function($digit) {
            return $digit->length() == 1;
        });
        
        $mapping['d'] = (string)$hardDigits->map(function($digit) use ($easyDigits, $mapping) {
            return $digit->replaceMatches('/[' . $easyDigits['7'] . $mapping['g'] . ']/', '');
        })
        ->first(function($digit) {
            return $digit->length() == 1;
        });

        $mapping['b'] = (string)$easyDigits['4']->replaceMatches('/[' . $easyDigits['1'] . $mapping['d']. ']/', '');

        $mapping['f'] = (string)$hardDigits->map(function($digit) use ($easyDigits, $mapping) {
            return $digit->replaceMatches('/[' . $mapping['a'] . $mapping['b'] . $mapping['d'] . $mapping['g'] . ']/', '');
        })
        ->first(function ($digit) {
            return $digit->length() == 1;
        });

        $mapping['c'] = (string)$easyDigits['1']->replace($mapping['f'], '');

        return array_flip($mapping);
    }

    private function number(Collection $digits, $mapping) {
        $numbers = [
            'abcefg' => '0',
            'cf' => '1',
            'acdeg' => '2',
            'acdfg' => '3',
            'bcdf' => '4',
            'abdfg' => '5',
            'abdefg' => '6',
            'acf' => '7',
            'abcdefg' => '8',
            'abcdfg' => '9'
        ];

        return (int)$digits->map(function($digit) use ($numbers, $mapping) {
            $segment = $digit->split(1)
                ->map(function($wire) use ($mapping) {
                    return $mapping[$wire];
                })
                ->sort()
                ->implode('');
            return $numbers[$segment];
        })
        ->implode('');
    }
}