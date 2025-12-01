import AOCKit

public enum Day1: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let moves = try RotationParser().parse(input[...])
        let state = moves.reduce(into: (position: 50, count: 0)) { state, move in
            state.position = move.direction(state.position, move.amount) % 100
            
            if state.position == 0 {
                state.count += 1
            }
        }
        
        return state.count
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let moves = try RotationParser().parse(input[...])
        let state = moves.reduce(into: (position: 50, count: 0)) { state, move in
            for value in stride(from: state.position, through: move.direction(state.position, move.amount), by: move.direction.step).dropFirst()
                where value % 100 == 0 {
                state.count += 1
            }
            
            state.position = move.direction(state.position, move.amount)
        }
        
        return state.count
    }
}

private struct Move {
    enum Direction: String, CaseIterable {
        case left = "L"
        case right = "R"
        
        var operation: (Int, Int) -> Int {
            switch self {
            case .left: return (-)
            case .right: return (+)
            }
        }
        
        var step: Int {
            switch self {
            case .left: return -1
            case .right: return 1
            }
        }
        
        func callAsFunction(_ a: Int, _ b: Int) -> Int {
            operation(a, b)
        }
    }
    
    let direction: Direction
    let amount: Int
}

private struct RotationParser: Parser {
    var body: some Parser<Substring, [Move]> {
        Parse {
            $0.map(Move.init)
        } with: {
            Many {
                Move.Direction.parser()
                
                Int.parser()
            } separator: {
                Whitespace(1, .vertical)
            }
        }
    }
}
