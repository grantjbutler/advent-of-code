import AOCKit

public struct Day1: Solution {
    public init() {}

    public func part1(_ input: Input) -> Int {
        return input.split(separator: "\n\n")
            .map {
                $0.lines
                    .integers
                    .sum()
            }
            .max()!
    }
    
    public func part2(_ input: Input) -> Int {
        input.split(separator: "\n\n")
            .map {
                $0.lines
                    .integers
                    .sum()
            }
            .sorted(in: .descending)
            .prefix(3)
            .sum()
    }
}
