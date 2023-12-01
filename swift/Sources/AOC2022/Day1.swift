import AOCKit

public struct Day1: Solution {
    public init() {}

    public func part1(_ input: String) -> Int {
        return input.split(separator: "\n\n")
            .map {
                $0.lines
                    .asIntegers
                    .sum()
            }
            .max()!
    }
    
    public func part2(_ input: String) -> Int {
        input.split(separator: "\n\n")
            .map {
                $0.lines
                    .asIntegers
                    .sum()
            }
            .sorted(in: .descending)
            .prefix(3)
            .sum()
    }
}
