<?php

namespace AOC\DataStructures;

use Illuminate\Support\Collection;

class Graph {
    private Collection $connections;

    public function __construct() {
        $this->connections = collect();
    }

    public function connect(mixed $a, mixed $b, $establishInverseConnection = true) {
        $this->connections->put($a, $this->connections->get($a, collect())->push($b));

        if ($establishInverseConnection) {
            $this->connections->put($b, $this->connections->get($b, collect())->push($a));
        }
    }

    public function connections(mixed $node): Collection {
        return $this->connections[$node];
    }
}
