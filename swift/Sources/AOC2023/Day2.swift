import AOCKit

private enum Color: String, CaseIterable {
    case blue
    case green
    case red
}

public struct Day2: Solution {
    public init() {}

    public func part1(_ input: String) throws -> some CustomStringConvertible {
        let bag = [
            Color.red: 12,
            Color.green: 13,
            Color.blue: 14
        ]
    
        return try input
            .lines
            .compactMap { line in
                let game = try gameParser.parse(line[...])
                
                return game.pulls.allSatisfy { pulls in
                    return pulls.allSatisfy { pair in
                        bag[pair.key, default: 0] >= pair.value
                    }
                } ? game.id : nil
            }
            .sum()
    }
    
    public func part2(_ input: String) throws -> some CustomStringConvertible {
        return try input
            .lines
            .compactMap { line in
                let game = try gameParser.parse(line[...])
                
                let bag = game.pulls.reduce(into: [Color: Int]()) { bag, pulls in
                    bag = pulls.reduce(into: bag) { bag, pair in
                        bag[pair.key] = max(bag[pair.key, default: 0], pair.value)
                    }
                }
                
                return bag.product(\.value)
            }
            .sum()
    }
}

private struct Game {
    let id: Int
    let pulls: [[Color: Int]]
}

private let gameParser = Parse(input: Substring.self) {
    Game(id: $0, pulls: $1.map { pairs in
        pairs.reduce(into: [Color: Int]()) { partial, next in
            partial[next.1] = next.0
        }
    })
} with: {
    "Game"
    Whitespace()
    Int.parser()
    ":"
    
    Many {
        Whitespace()
        
        Many {
            Int.parser()
            Whitespace()
            Color.parser()
        } separator: {
            ", "
        }
    } separator: {
        ";"
    }
}
