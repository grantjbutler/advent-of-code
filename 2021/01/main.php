<?php

function countIncreased($depths) {
    $count = 0;
    $previous = null;

    foreach ($depths as $depth) {
        if ($previous == null) {
            $previous = $depth;
            continue;
        }

        if ($depth > $previous) {
            $count += 1;
        }

        $previous = $depth;
    }

    return $count;
}

$depths = array_map(function ($item) { return (int)trim($item); }, file('input.txt'));

$count = countIncreased($depths);

echo "Depth increased {$count} times\n";

$sums = [];
for ($i = 0; $i + 2 < count($depths); $i++) {
    array_push($sums, $depths[$i] + $depths[$i + 1] + $depths[$i + 2]);
}

$sumsCount = countIncreased($sums);

echo "Depth sum increased {$sumsCount} times\n";