import AOCKit

private let mapping = [
    "one": "1",
    "two": "2",
    "three": "3",
    "four": "4",
    "five": "5",
    "six": "6",
    "seven": "7",
    "eight": "8",
    "nine": "9"
]

public struct Day1: Solution {
    public init() {}

    public func part1(_ input: Input) -> Int {
        input
            .lines
            .map(\.digits)
            .map { Int("\($0.first!)\($0.last!)")! }
            .sum()
    }
    
    public func part2(_ input: Input) -> Int {
        input
            .lines
            .map { (line: Input) -> Input in
                return mapping.reduce(into: Input(line.buffer)) { (partialResult: inout Input, pair: (key: String, value: String)) in
                    partialResult.replace(pair.key, with: pair.key + pair.value + String(pair.key.last!))
                }
            }
            .map(\.digits)
            .map { Int("\($0.first!)\($0.last!)")! }
            .sum()
    }
}
