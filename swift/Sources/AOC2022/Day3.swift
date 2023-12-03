import AOCKit
import Algorithms

public enum Day3: Solution {
    public static func part1(_ input: String) -> Int {
        input
            .lines
            .flatMap { line in
                return line
                    .evenlyChunked(in: 2)
                    .uniqued()
                    .commonElements()
                    .map { $0.indexInAlphabet + ($0.isUppercase ? 27 : 1) }
            }
            .sum()
    }
    
    public static func part2(_ input: String) -> Int {
        input
            .lines
            .chunks(ofCount: 3)
            .flatMap { chunk in
                chunk
                    .uniqued()
                    .commonElements()
                    .map { $0.indexInAlphabet + ($0.isUppercase ? 27 : 1) }
            }
            .sum()
    }
}
