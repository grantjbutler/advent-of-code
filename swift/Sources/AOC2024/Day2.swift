import AOCKit

public enum Day2: Solution {
    public typealias SolutionInput = [[Int]]

    public static func transformInput(_ input: String) throws -> [[Int]] {
        return try input
            .lines
            .parse(using: Parse(input: Substring.self) {
                Many {
                    Int.parser()
                } separator: {
                    Whitespace()
                }
            })
    }
    
    private static func isSafe(_ line: some Sequence<Int>) -> Bool {
        let differences = zip(line, line.dropFirst())
            .map { a, b in
                return b - a
            }
        
        return (differences.allSatisfy { $0 > 0 }
            || differences.allSatisfy { $0 < 0 })
            && differences.allSatisfy { abs($0) <= 3 }
    }

    public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
        return input
            .count(where: isSafe)
    }
    
    public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
        return input
            .count { line in
                if isSafe(line) {
                    return true
                }
                
                for i in line.indices {
                    var line = line
                    line.remove(at: i)
                    if isSafe(line) { return true }
                }
                
                return false
            }
    }
}
