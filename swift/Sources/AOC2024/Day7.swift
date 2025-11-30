import AOCKit

public enum Day7: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        enum Operation: Hashable {
            case addition
            case multiplication
        }
        
        let equations = try InputParser().parse(input[...])
            .filter { target, values in
                let operations = Array(repeating: Operation.addition, count: values.count - 1)
                    + Array(repeating: Operation.multiplication, count: values.count - 1)
                
                for combinations in operations.uniquePermutations(ofCount: values.count - 1) {
                    let calculation = zip(values.dropFirst(), combinations).reduce(into: values.first!) { partialResult, pair in
                        switch pair.1 {
                        case .addition:
                            partialResult += pair.0
                        case .multiplication:
                            partialResult *= pair.0
                        }
                    }
                    
                    if calculation == target { return true }
                }
                
                return false
            }
        
        return equations
            .sum(\.0)
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        enum Operation: Hashable {
            case addition
            case multiplication
            case concatenation
        }
        
        let equations = try InputParser().parse(input[...])
            .filter { target, values in
                let operations = Array(repeating: Operation.addition, count: values.count - 1)
                    + Array(repeating: Operation.multiplication, count: values.count - 1)
                    + Array(repeating: Operation.concatenation, count: values.count - 1)
                
                for combinations in operations.uniquePermutations(ofCount: values.count - 1) {
                    let calculation = zip(values.dropFirst(), combinations).reduce(into: values.first!) { partialResult, pair in
                        switch pair.1 {
                        case .addition:
                            partialResult += pair.0
                        case .multiplication:
                            partialResult *= pair.0
                        case .concatenation:
                            partialResult = Int(String(partialResult) + String(pair.0))!
                        }
                    }
                    
                    if calculation == target { return true }
                }
                
                return false
            }
        
        return equations
            .sum(\.0)
    }
}

private struct InputParser: Parser {
    var body: some Parser<Substring.UTF8View, [(Int, [Int])]> {
        Many {
            LineParser()
        } separator: {
            Whitespace(.vertical)
        }
    }
}

private struct LineParser: Parser {
    var body: some Parser<Substring.UTF8View, (Int, [Int])> {
        Int.parser()
        
        ": ".utf8
        
        Many {
            Int.parser()
        } separator: {
            Whitespace(.horizontal)
        }
    }
}
