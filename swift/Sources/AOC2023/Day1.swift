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

public enum Day1: Solution {
    public static func part1(_ input: String) -> some CustomStringConvertible {
        input
            .lines
            .map(\.digits)
            .map { Int("\($0.first!)\($0.last!)")! }
            .sum()
    }
    
    public static func part2(_ input: String) -> some CustomStringConvertible {
        input
            .lines
            .map { (line: String) -> String in
                return mapping.reduce(into: line) { partialResult, pair in
                    partialResult.replace(pair.key, with: pair.key + pair.value + String(pair.key.last!))
                }
            }
            .map(\.digits)
            .map { Int("\($0.first!)\($0.last!)")! }
            .sum()
    }
}
