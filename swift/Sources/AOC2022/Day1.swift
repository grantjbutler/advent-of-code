import AOCKit

public enum Day1: Solution {
    public static func part1(_ input: String) -> Int {
        return input.split(separator: "\n\n")
            .map {
                $0.lines
                    .asIntegers
                    .sum()
            }
            .max()!
    }
    
    public static func part2(_ input: String) -> Int {
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
