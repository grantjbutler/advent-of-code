import AOCKit

private enum Color: String, CaseIterable {
    case blue
    case green
    case red
    
    var amountInBag: Int {
        switch self {
        case .blue: return 14
        case .green: return 13
        case .red: return 12
        }
    }
}

public enum Day2: Solution {
    public static func part1(_ input: String) throws -> some CustomStringConvertible {
        return try input
            .lines
            .parse(using: gameParser)
            .compactMap { game in
                return game.pulls.allSatisfy { pulls in
                    return pulls.allSatisfy { pair in
                        pair.key.amountInBag >= pair.value
                    }
                } ? game.id : nil
            }
            .sum()
    }
    
    public static func part2(_ input: String) throws -> some CustomStringConvertible {
        return try input
            .lines
            .parse(using: gameParser)
            .compactMap { game in
                return game.pulls.reduce(into: [Color: Int]()) { bag, pulls in
                    bag = pulls.reduce(into: bag) { bag, pair in
                        bag[pair.key] = max(bag[pair.key, default: 0], pair.value)
                    }
                }
                .product(\.value)
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
