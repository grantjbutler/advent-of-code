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
    }

    private function loadDay() {
        if (($day = $this->argument('day'))) {
            if ($day == 'next') {
                return [now()->year, now()->addDay()->day];
            }
            
            return explode(':', $day);
        }

        return [now()->year, now()->day];
    }

    private function createDay($year, $day) {
        $folder = implode(DIRECTORY_SEPARATOR, [app()->getBasePath(), 'src', 'AOC' . $year, 'Day' . $day]);
        $file = $folder . DIRECTORY_SEPARATOR . 'Day' . $day . '.php';
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
        if ($filesystem->exists($file)) {
            $this->info('File already exists for day ' . $day . '. Skipping creation.');

            return;
        }

        $filesystem->mkdir($folder);
        file_put_contents($file, $template);

        $this->info('Successfully created file for day ' . $day);
    }
}