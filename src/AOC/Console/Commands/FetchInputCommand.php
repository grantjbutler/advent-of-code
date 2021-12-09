<?php

namespace AOC\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Console\Command;
use Symfony\Component\Filesystem\Filesystem;

class FetchInputCommand extends Command {
    protected $signature = 'fetch:input {day?} {--wait}';

    protected $description = 'Fetches input for a given day, ';

    private $filesystem;

    public function __construct() {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    public function handle() {
        if (!$this->filesystem->exists(app()->getBasePath() . '/.session')) {
            $this->error('No session cookie found. Use fetch:set-cookie to set a cookie.');
            return 1;
        }

        [$year, $day] = $this->loadDay();

        $this->info('Fetching input for Day ' . $day . ', ' . $year);

        $input = $this->fetchInput($year, $day);
        $this->writeInput($year, $day, $input);
        
        if ($this->option('wait')) {
            $this->beep();
        }
    }

    private function loadDay() {
        if ($this->option('wait')) {
            return [now()->year, now()->day + 1];
        }

        if (($day = $this->argument('day'))) {
            return explode(':', $day);
        }

        return [now()->year, now()->day];
    }

    private function fetchInput($year, $day) {
        if ($this->option('wait')) {
            $this->info('Waiting until input is available...');

            time_sleep_until(now()->startOfDay()->addDay()->getTimestamp());
        }

        $url = 'https://adventofcode.com/' . $year . '/day/' . $day . '/input';
        $jar = new CookieJar();
        $jar->setCookie(new SetCookie([
            'Name' => 'session',
            'Value' => file_get_contents(app()->getBasePath() . '/.session'),
            'Domain' => '.adventofcode.com',
            'HttpOnly' => true,
            'Secure' => true

        ]));

        $client = new Client([
            'cookies' => $jar
        ]);
        $response = $client->get($url);
        return (string)$response->getBody();
    }

    private function writeInput($year, $day, $input) {
        $folder = implode(DIRECTORY_SEPARATOR, [app()->getBasePath(), 'src', 'AOC' . $year, 'Day' . $day]);
        
        $this->filesystem->mkdir($folder);
        file_put_contents($folder . DIRECTORY_SEPARATOR . 'input.txt', $input);
    }

    private function beep() {
        for ($i = 0; $i < 5; $i++) {
            fprintf ( STDOUT, "%s", "\x07" );
            usleep(500000);
        }
    }
}