<?php

$file = fopen('input.txt', 'r');
if (!$file) {
    die('could not open input file');
}

$storage = [];

while (($line = fgets($file)) !== false) {
    $line = trim($line);

    $entry = (int)$line;
    if ($entry == 0) { continue; }

    if (array_key_exists($line, $storage)) {
        echo "found that {$entry} + {$storage[$line]} = 2020\n";

        $total = $entry * $storage[$line];
        echo "Multipied, they are {$total}\n";
        
        break;
    } else {
        $operand = 2020 - $entry;
        $key = (string)$operand;

        $storage[$key] = $entry;
    }
}