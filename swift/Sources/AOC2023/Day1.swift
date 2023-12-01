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
            .map { $0.buffer.filter { $0.isNumber } }
            .map { Int("\($0.first!)\($0.last!)")! }
            .sum()
    }
    
    public func part2(_ input: Input) -> Int {
        input
            .lines
            .map { (line: Input) -> String in
                return dump(mapping.reduce(into: String(line.buffer)) { (partialResult: inout String, pair: (key: String, value: String)) in
                    partialResult.replace(pair.key, with: pair.key + pair.value + String(pair.key.last!))
                })
            }
            .map { $0.filter { $0.isNumber } }
            .map { dump(Int("\($0.first!)\($0.last!)")!) }
            .sum()
    }
}
