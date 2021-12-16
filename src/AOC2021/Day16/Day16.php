<?php

namespace AOC2021\Day16;

use AOC\Day;
use AOC\Input;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Day16 extends Day {
    public function part1(Input $input) {
        $packet = $this->parsePacket($input->lines[0]);
        return $this->sumVersion($packet);
    }

    public function part2(Input $input) {
        $packet = $this->parsePacket($input->lines[0]);
        return $packet->evaluate();
    }

    private function parsePacket(string $packet) {
        $parser = new PacketParser;

        return $parser->parse(Str::of($this->toBinary($packet)));
    }

    private function sumVersion(Packet $packet) {
        if ($packet->type == 4) {
            return $packet->version;
        }

        return $packet->version + $packet->data->reduce(fn ($total, $packet) => $total + $this->sumVersion($packet), 0);
    }

    private function toBinary(string $hex): string {
        $string = '';
        for ($i = 0; $i < strlen($hex); $i++) {
            $string .= [
                '0' => '0000',
                '1' => '0001',
                '2' => '0010',
                '3' => '0011',
                '4' => '0100',
                '5' => '0101',
                '6' => '0110',
                '7' => '0111',
                '8' => '1000',
                '9' => '1001',
                'A' => '1010',
                'B' => '1011',
                'C' => '1100',
                'D' => '1101',
                'E' => '1110',
                'F' => '1111',
            ][$hex[$i]];
        }

       return $string;
    }
}

class Packet {
    public function __construct(public int $version, public int $type, public $data) {}

    function evaluate() {
        switch ($this->type) {
            case 0:
                return $this->data->reduce(function($total, $packet) {
                    return $total + $packet->evaluate();
                }, 0);
            case 1:
                return $this->data->reduce(function($total, $packet) {
                    return $total * $packet->evaluate();
                }, 1);
            case 2:
                if ($this->data->count() == 1) {
                    return $this->data[0]->evaluate();
                }
                return min(...$this->data->map->evaluate());
            case 3:
                if ($this->data->count() == 1) {
                    return $this->data[0]->evaluate();
                }
                return max(...$this->data->map->evaluate());
            case 4:
                return $this->data;
            case 5:
                return $this->data[0]->evaluate() > $this->data[1]->evaluate() ? 1 : 0;
            case 6:
                return $this->data[0]->evaluate() < $this->data[1]->evaluate() ? 1 : 0;
            case 7:
                return $this->data[0]->evaluate() == $this->data[1]->evaluate() ? 1 : 0;
        }
    }
}

class PacketParser {
    private function consume(Stringable &$stringable, $length) {
        $data = $stringable->substr(0, $length);
        $stringable = $stringable->substr($length);
        return $data;
    }

    function parse(Stringable &$packet): Packet {
        $version = $this->consume($packet, 3)->toDecimal(2);
        $type = $this->consume($packet, 3)->toDecimal(2);

        switch ($type) {
            case 4:
                return new Packet($version, $type, $this->parseLiteral($packet));
            default:
                return new Packet($version, $type, $this->parseSubPackets($packet));

        }
    }

    private function parseLiteral(Stringable &$data) {
        $string = '';
        while (true) {
            $digit = $this->consume($data, 5);
            $string .= $digit->substr(1);

            if ($digit->substr(0, 1) == '0') {
                break;
            }
        }
        return bindec($string);
    }

    private function parseSubPackets(Stringable &$data) {
        $type = $this->consume($data, 1);
        $packets = collect();

        switch ($type) {
            case '0':
                $length = $this->consume($data, 15)->toDecimal(2);
                $packetData = $this->consume($data, $length);
                
                while ($packetData->isNotEmpty()) {
                    $packets->push($this->parse($packetData));
                }
                break;

            case '1':
                $length = $this->consume($data, 11)->toDecimal(2);
                for ($i = 0; $i < $length; $i++) {
                    $packets->push($this->parse($data));
                }
                break;
        }

        return $packets;
    }
}