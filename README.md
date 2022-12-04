# advent-of-code

Welcome! These are my solutions for [Advent of Code](https://adventofcode.com/). My solutions are written in PHP, and use a number of helpers I've extracted over time to help reduce duplication.

## aoc

Part of this repo contains the `aoc` command line tool for automating certain tasks related to Advent of Code:

- `php aoc make <date>` will create a new empty file for me to write my solution into, following the general structure that I've set up. Usually, you want to specify `next` forte the date, so it will create the file to tomorrow's problem, but you can provide any date in the format `yyyy:d`.
- `php aoc run` will run my code for the given day against my input, printing out the results. If I don't want to run the code for today's problem, I can specify which day I want in the format `yyyy:d`. I can also run test input using the `--test` flag (where test input is usually the example that's given in the problem that has the expected result specified, allowing me to verify that my solution works).
- `php aoc fetch:input` will fetch my input file from the Advent of Code site and save it to a text file. If you specify the `--wait` flag, the command will wait until midnight Eastern time to fetch input. You can also add the `--open` flag, which will open the problem's web page once the input has been fetched.
- `php aoc fetch:set-cookie <cookie>` will write your session cookie for the Advent of Code website to disk, so that your specific input can be fetched. The value of the cookie can be gotten from the Advent of Code website, usually by using the web inspector or other debug tool to find the value of the `session` cookie.