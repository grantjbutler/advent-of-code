<?php

class Count {
    public int $zeroes = 0;
    public int $ones = 0;
}

$lines = array_map(function ($item) { return trim($item); }, file('input.txt'));
$counts = [];
for ($i = 0, $len = strlen($lines[0]); $i < $len; $i++) {
    array_push($counts, new Count());
}

foreach($lines as $line) {
    for ($i = 0, $len = strlen($line); $i < $len; $i++) {
        $bit = (int)$line[$i];

        if ($bit == 0) {
            $counts[$i]->zeroes += 1;
        } else {
            $counts[$i]->ones += 1;
        }
    }
}

$gammaRate = '';
$epsilonRate = '';

foreach($counts as $count) {
    if ($count->ones < $count->zeroes) {
        $gammaRate .= '0';
        $epsilonRate .= '1';
    } else {
        $gammaRate .= '1';
        $epsilonRate .= '0';
    }
}

$gammaRate = bindec($gammaRate);
$epsilonRate = bindec($epsilonRate);
$powerConsumption = $gammaRate * $epsilonRate;

echo "Gamma rate is {$gammaRate}\n";
echo "Eplison rate is {$epsilonRate}\n";
echo "Power consumption is {$powerConsumption}\n";

function filterInput($input, $index, $comparator) {
    $entries = ['0' => [], '1' => []];
    
    foreach ($input as $entry) {
        $bit = $entry[$index];
        array_push($entries[$bit], $entry);
    }

    if ($comparator($entries['0'], $entries['1'])) {
        return $entries['0'];
    } else {
        return $entries['1'];
    }
}

$input = $lines;
for ($i = 0, $len = count($counts); $i < $len; $i++) {
    $input = filterInput($input, $i, function($lhs, $rhs) { return count($lhs) > count($rhs); });

    if (count($input) == 1) {
        break;
    }
}

$oxygenGeneratorRating = bindec($input[0]);

$input = $lines;
for ($i = 0, $len = count($counts); $i < $len; $i++) {
    $input = filterInput($input, $i, function($lhs, $rhs) { return count($lhs) <= count($rhs); });

    if (count($input) == 1) {
        break;
    }
}

$co2ScrubberRating = bindec($input[0]);

$lifeSupportRating = $oxygenGeneratorRating * $co2ScrubberRating;

echo "Life Support Rating is {$lifeSupportRating}\n";