<?php

namespace AOC\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Filesystem\Filesystem;

class MakeCommand extends Command {
    protected $signature = 'make {day?}';

    protected $description = 'Creates the basic structure for a day\'s problem.';

    public function handle() {
        [$year, $day] = $this->loadDay();
        $this->createDay($year, $day);

        $this->info('Successfully created file for day ' . $day);
    }

    private function loadDay() {
        if (($day = $this->argument('day'))) {
            return explode(':', $day);
        }

        return [now()->year, now()->day];
    }

    private function createDay($year, $day) {
        $folder = implode(DIRECTORY_SEPARATOR, [app()->getBasePath(), 'src', 'AOC' . $year, 'Day' . $day]);
        $template = <<<PHP
        <?php

        namespace AOC{$year}\Day{$day};

        use AOC\Day;
        use AOC\Input;

        class Day{$day} extends Day {
            public function part1(Input \$input) {

            }

            public function part2(Input \$input) {

            }
        }
        PHP;

        $filesystem = new Filesystem();
        $filesystem->mkdir($folder);
        file_put_contents($folder . DIRECTORY_SEPARATOR . 'Day' . $day . '.php', $template);
    }
}