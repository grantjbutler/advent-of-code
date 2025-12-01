import AOCKit

public enum Day6: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try Part1Parser().parse(input[...])
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
        let (time, record) = try Part2Parser().parse(input[...])
        
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

private struct RaceParser: Parser {
    var body: some Parser<Substring, (times: [Int], distances: [Int])> {
        Parse {
            "Time:"
            Whitespace()
            
            IntegersParser()
            
            Whitespace(1, .vertical)
            
            "Distance:"
            Whitespace()
            
            IntegersParser()
        }
        .map { times, distances in
            (times: times, distances: distances)
        }
    }
}

private struct Part1Parser: Parser {
    var body: some Parser<Substring, [(time: Int, distance: Int)]> {
        RaceParser()
            .map { times, distances in
                return zip(times, distances).map { (time: $0, distance: $1) }
            }
    }
}

private struct Part2Parser: Parser {
    var body: some Parser<Substring, (Int, Int)> {
        RaceParser()
            .map { times, distances in
                (
                    Int(times.reduce(into: "") { partialResult, int in
                        partialResult += "\(int)"
                    })!,
                    Int(distances.reduce(into: "") { partialResult, int in
                        partialResult += "\(int)"
                    })!
                )
            }
    }
}
