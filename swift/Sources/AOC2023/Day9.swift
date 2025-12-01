import AOCKit

public enum Day9: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try input
            .lines
            .map { line in
                let numbers = try IntegersParser().parse(line[...])
                
                var sequences: [[Int]] = [numbers]
                var currentSequence = numbers
                
                repeat {
                    let subtractionSequence = currentSequence
                        .windows(ofCount: 2)
                        .map { $0.last! - $0.first! }
                    
                    sequences.append(subtractionSequence)
                    
                    if subtractionSequence.allSatisfy({ $0 == .zero }) { break }
                    currentSequence = subtractionSequence
                } while true
                
                return sequences
                    .reversed()
                    .reduce(into: 0) { partialResult, sequence in
                        partialResult = partialResult + sequence.last!
                    }
            }
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try input
            .lines
            .map { line in
                let numbers = try IntegersParser().parse(line[...])
                
                var sequences: [[Int]] = [numbers]
                var currentSequence = numbers
                
                repeat {
                    let subtractionSequence = currentSequence
                        .windows(ofCount: 2)
                        .map { $0.last! - $0.first! }
                    
                    sequences.append(subtractionSequence)
                    
                    if subtractionSequence.allSatisfy({ $0 == .zero }) { break }
                    currentSequence = subtractionSequence
                } while true
                
                return sequences
                    .reversed()
                    .reduce(into: 0) { partialResult, sequence in
                        partialResult = sequence.first! - partialResult
                    }
            }
            .sum()
    }
}
