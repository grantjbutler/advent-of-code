<?php

class Point {
    public int $x;
    public int $y;

    function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    function __toString() {
        return "{$this->x},{$this->y}";
    }
}

class Line {
    public Point $start;
    public Point $end;

    function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    function __toString() {
        return "{$this->start} -> {$this->end}";
    }
}

class Map {
    public $size;
    public $values;

    public function __construct($size) {
        $this->size = $size;
        $this->values = array_fill(0, $size['width'] * $size['height'], 0);
    }

    public function __toString() {
        $string = '';
        foreach(array_chunk($this->values, $this->size['width']) as $row) {
            $string .= implode('', array_map(function ($item) { return $item == 0 ? '.' : $item; }, $row)) . "\n";
        }
        return $string;
    }
}

$lines = array_filter(array_map(function($line) {
    preg_match('/(?<x1>[0-9]+),(?<y1>[0-9]+)\s->\s(?<x2>[0-9]+),(?<y2>[0-9]+)/', trim($line), $matches);

    return new Line(
        new Point((int)$matches['x1'], (int)$matches['y1']),
        new Point((int)$matches['x2'], (int)$matches['y2'])
    );
}, file('input.txt')), function ($line) {
    return true; // $line->start->x == $line->end->x || $line->start->y == $line->end->y;
});

$size = ['width' => 0, 'height' => 0];
foreach($lines as $line) {
    $size['width'] = max($size['width'], $line->start->x, $line->end->x);
    $size['height'] = max($size['height'], $line->start->y, $line->end->y);
}
$size['width'] += 1;
$size['height'] += 1;

$map = new Map($size);

foreach($lines as $line) {
    if ($line->start->x != $line->end->x && $line->start->y != $line->end->y) {
        $iterator = new MultipleIterator();
        $iterator->attachIterator(new ArrayIterator(range($line->start->x, $line->end->x)));
        $iterator->attachIterator(new ArrayIterator(range($line->start->y, $line->end->y)));

        foreach($iterator as [$x, $y]) {
            $map->values[$y * $size['width'] + $x] += 1;
        }
    } else if ($line->start->x != $line->end->x) {
        foreach(range($line->start->x, $line->end->x) as $x) {
            $map->values[$line->start->y * $size['width'] + $x] += 1;
        }
    } else if ($line->start->y != $line->end->y) {
        foreach (range($line->start->y, $line->end->y) as $y) {
            $map->values[$y * $size['width'] + $line->start->x] += 1;
        }
    }
}

$overlaps = array_reduce($map->values, function($total, $value) {
    if ($value >= 2) {
        return $total + 1;
    }

    return $total;
}, 0);

echo $overlaps;