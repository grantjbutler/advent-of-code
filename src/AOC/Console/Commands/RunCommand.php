<?php

namespace AOC\Console\Commands;

use AOC\Day;
use AOC\Input;
use Illuminate\Console\Command;

class RunCommand extends Command {
    protected $signature = 'run {day?} {--test}';

    protected $description = 'Runs the day\'s problem.';

    public function handle() {
        [$year, $day] = $this->loadDay();
        
        $this->info('Running problem for Day ' . $day . ', ' . $year);
        
        $input = $this->loadInput($year, $day);
        $day = $this->createDay($year, $day);
        
        $start = microtime(true);
        $this->info('Part 1: ' . $day->part1($input));
        $end = microtime(true);

        $this->info('Part 1 executed in ' . $end - $start . ' seconds');

        $start = microtime(true);
        $this->info('Part 2: ' . $day->part2($input));
        $end = microtime(true);

        $this->info('Part 2 executed in ' . $end - $start . ' seconds');
    }

    private function loadDay() {
        if (($day = $this->argument('day'))) {
            return explode(':', $day);
        }

        return [now()->year, now()->day];
    }

    private function createDay($year, $day): Day {
        $class = 'AOC' . $year . '\\Day' . $day . '\\Day' . $day;
        return new $class;
    }

    private function loadInput($year, $day): Input {
        $inputFolder = implode(DIRECTORY_SEPARATOR, [app()->getBasePath(), 'src', 'AOC' . $year, 'Day' . $day]);
        if ($this->option('test')) {
            return new Input($inputFolder . DIRECTORY_SEPARATOR . 'test.txt');
        } else {
            return new Input($inputFolder . DIRECTORY_SEPARATOR . 'input.txt');
        }
    }
}