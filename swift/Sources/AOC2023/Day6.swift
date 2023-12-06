import AOCKit

public enum Day6: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try raceParser.parse(input[...])
            .map { time, record in
                return (1..<time).map { pressDuration in
                    let distance = pressDuration * (time - pressDuration)
                    return distance > record ? 1 : 0
                }
                .sum()
            }
            .product()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let (time, record) = try part2Parser.parse(input[...])
        
        let lowerBound = (1..<time).first(where: { pressDuration in
            let distance = pressDuration * (time - pressDuration)
            return distance > record
        })!
        
        var upperBound = (time - 1)
        repeat {
            let distance = upperBound * (time - upperBound)
            if distance > record { break }
            
            upperBound -= 1
        } while upperBound > lowerBound
        
        return upperBound - lowerBound + 1
    }
}

private let raceParser = Parse(input: Substring.self) { times, distances in
    return zip(times, distances).map { (time: $0, distance: $1) }
} with: {
    "Time:"
    Whitespace()
    
    Many {
        Int.parser()
    } separator: {
        Whitespace()
    }
    
    Whitespace(1, .vertical)
    
    "Distance:"
    Whitespace()
    
    Many {
        Int.parser()
    } separator: {
        Whitespace()
    }
}

private let part2Parser = Parse(input: Substring.self) {
    "Time:"
    Whitespace()
    
    Many {
        Int.parser()
    } separator: {
        Whitespace()
    }
    .map { ints in
        Int(ints.reduce(into: "") { partialResult, int in
            partialResult += "\(int)"
        })!
    }
    
    Whitespace(1, .vertical)
    
    "Distance:"
    Whitespace()
    
    Many {
        Int.parser()
    } separator: {
        Whitespace()
    }
    .map { ints in
        Int(ints.reduce(into: "") { partialResult, int in
            partialResult += "\(int)"
        })!
    }
}
