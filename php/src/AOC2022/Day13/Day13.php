<?php

namespace AOC2022\Day13;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Arr;

enum Ordering {
    case IN_RIGHT_ORDER;
    case IN_WRONG_ORDER;
    case UNKNOWN;
}

class Day13 extends Day {
    public function part1(Input $input) {
        return $input->explode("\n\n")
            ->map(fn ($group) => $group->explode("\n"))
            ->map(fn ($pairs) => $this->validatePackets($pairs))
            ->map(fn ($item, $key) => $item ? $key + 1 : 0)
            ->sum();
    }

    public function part2(Input $input) {
        $self = $this;

        $packets = $input->explode("\n")
            ->filter(fn ($packet) => strlen($packet))
            ->map(fn ($packet) => json_decode($packet))
            ->concat([[[2]], [[6]]])
            ->sort(function ($lhs, $rhs) use ($self) {
                switch ($self->check($lhs, $rhs)) {
                    case Ordering::IN_RIGHT_ORDER:
                        return -1;
                    case Ordering::IN_WRONG_ORDER:
                        return 1;
                    case Ordering::UNKNOWN:
                        return 0;
                }
            })
            ->map(fn ($packet) => json_encode($packet))
            ->values();
        
        return ($packets->search("[[2]]") + 1) * ($packets->search("[[6]]") + 1);
    }

    private function validatePackets($packets): bool {
        [$left, $right] = $packets->map(fn ($packet) => json_decode($packet));

        return $this->check($left, $right) == Ordering::IN_RIGHT_ORDER;
    }

    private function check($left, $right): Ordering {
        if (!is_array($left) || !is_array($right)) {
            return $this->check(Arr::wrap($left), Arr::wrap($right), true);
        }

        $length = min(count($left), count($right));

        $pairs = collect($left)->zip($right)
            ->take($length);
        
        foreach ($pairs as $item) {
            [$lhs, $rhs] = $item;

            if (is_array($lhs) || is_array($rhs)) {
                switch ($this->check($lhs, $rhs)) {
                    case Ordering::UNKNOWN:
                        break;
                    case Ordering::IN_RIGHT_ORDER:
                        return Ordering::IN_RIGHT_ORDER;
                    case Ordering::IN_WRONG_ORDER:
                        return Ordering::IN_WRONG_ORDER;
                }
            } else {
                if ($lhs == $rhs) {
                    continue;
                }
                
                if ($lhs < $rhs) {
                    return Ordering::IN_RIGHT_ORDER;
                } else {
                    return Ordering::IN_WRONG_ORDER;
                }
            }
        }

        if (count($left) < count($right)) {
            return Ordering::IN_RIGHT_ORDER;
        } else if (count($left) > count($right)) {
            return Ordering::IN_WRONG_ORDER;
        } else {
            return Ordering::UNKNOWN;
        }
    }
}