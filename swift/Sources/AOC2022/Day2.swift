import AOCKit

enum Move {
    case rock
    case paper
    case scissor
    
    init?(_ string: String) {
        switch string {
        case "A", "X": self = .rock
        case "B", "Y": self = .paper
        case "C", "Z": self = .scissor
        default: return nil
        }
    }
    
    var score: Int {
        switch self {
        case .rock: return 1
        case .paper: return 2
        case .scissor: return 3
        }
    }
    
    func result(againstOpponent opponent: Move) -> GameResult {
        switch (self, opponent) {
        case (.paper, .paper), (.rock, .rock), (.scissor, .scissor): return .draw
        case (.paper, .rock): return .win
        case (.paper, .scissor): return .loss
        case (.rock, .paper): return .loss
        case (.rock, .scissor): return .win
        case (.scissor, .paper): return .win
        case (.scissor, .rock): return .loss
        }
    }
    
    func roundScore(againstOpponent opponent: Move) -> Int {
        return score + result(againstOpponent: opponent).score
    }
}

enum GameResult {
    case loss
    case draw
    case win
    
    init?(_ string: String) {
        switch string {
        case "X": self = .loss
        case "Y": self = .draw
        case "Z": self = .win
        default: return nil
        }
    }
    
    var score: Int {
        switch self {
        case .loss: return 0
        case .draw: return 3
        case .win: return 6
        }
    }
    
    func move(againstOpponent opponent: Move) -> Move {
        switch (self, opponent) {
        case (.draw, _): return opponent
        case (.loss, .rock): return .scissor
        case (.win, .rock): return .paper
        case (.loss, .paper): return .rock
        case (.win, .paper): return .scissor
        case (.loss, .scissor): return .paper
        case (.win, .scissor): return .rock
        }
    }
    
    func roundScore(againstOpponent opponent: Move) -> Int {
        return move(againstOpponent: opponent).roundScore(againstOpponent: opponent)
    }
}

public struct Day2: Solution {
    public init() {}

    public func part1(_ input: String) -> Int {
        return input
            .lines
            .compactMap { line in
                let components = line.split(separator: " ")
                
                guard let opponent = Move(String(components[0])), let me = Move(String(components[1])) else { return nil }
                
                return me.roundScore(againstOpponent: opponent)
            }
            .sum()
    }
    
    public func part2(_ input: String) -> Int {
        return input
            .lines
            .compactMap { line in
                let components = line.split(separator: " ")
                
                guard let opponent = Move(String(components[0])), let result = GameResult(String(components[1])) else { return nil }
                
                return result.roundScore(againstOpponent: opponent)
            }
            .sum()
    }
}
