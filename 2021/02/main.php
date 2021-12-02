<?php

class Point {
    public int $x;
    public int $y;

    function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }
}

$location = new Point(0, 0);

foreach(file('input.txt') as $command) {
    [$command, $arg] = explode(" ", $command);

    switch ($command) {
        case "forward":
            $location->x += (int)$arg;
            break;
        case "down":
            $location->y += (int)$arg;
            break;
        case "up":
            $location->y -= (int)$arg;
            break;
    }
}

echo "Final location: {$location->x}, {$location->y}\n";

class Submarine {
    public int $position = 0;
    public int $depth = 0;
    public int $aim = 0;

    public function forward(int $amount) {
        $this->position += $amount;
        $this->depth += $amount * $this->aim;
    }

    public function up(int $amount) {
        $this->aim -= $amount;
    }

    public function down(int $amount) {
        $this->aim += $amount;
    }
}

$sub = new Submarine();

foreach(file('input.txt') as $command) {
    [$command, $arg] = explode(" ", $command);
    $sub->{$command}((int)$arg);
}

echo "Final location: {$sub->position}, {$sub->depth}\n";