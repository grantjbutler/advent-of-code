import AOCKit
import Foundation

public enum Day1: Solution {
    public typealias SolutionInput = ([Int], [Int])
    
    public static func transformInput(_ input: String) throws -> ([Int], [Int]) {
        var lists = input.lines
            .map {
                return $0.split(whereSeparator: { $0.isWhitespace })
                    .filter { !$0.isEmpty }
                    .compactMap { Int(String($0)) }
            }
            .reduce(into: ([Int](), [Int]())) { accumulated, pairs in
                accumulated.0.append(pairs[0])
                accumulated.1.append(pairs[1])
            }
        
        lists.0.sort()
        lists.1.sort()
        
        return lists
    }

    public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
        return zip(input.0, input.1)
            .map { pairs in
                return abs(pairs.0 - pairs.1)
            }
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
        let counts = NSCountedSet(array: input.1)
        
        return input.0
            .map { num in
                return num * counts.count(for: num)
            }
            .sum()
    }
}
