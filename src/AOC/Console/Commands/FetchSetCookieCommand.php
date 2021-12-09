<?php

namespace AOC\Console\Commands;

use Illuminate\Console\Command;

class FetchSetCookieCommand extends Command {
    protected $signature = 'fetch:set-cookie {cookie}';

    protected $description = 'Sets the cookie to use for fetching input.';

    public function handle() {
        file_put_contents(app()->getBasePath() . '/.session', $this->argument('cookie'));

        $this->info('Cookie saved!');
    }
}