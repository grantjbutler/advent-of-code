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

        $this->runPart(1, $day, $input);
        $this->runPart(2, $day, $input);
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

    private function runPart(int $part, Day $day, Input $input) {
        $function = 'part' . $part;

        $start = microtime(true);
        $result = $day->{$function}($input);
        $end = microtime(true);

        if (is_null($result)) {
            throw new \Exception("Part {$part} didn't return a result. Are you missing a return statement?");
        }

        $this->info("Part {$part}: {$result}");
        $this->info("Part {$part} executed in ". $end - $start . " seconds");

    }
}