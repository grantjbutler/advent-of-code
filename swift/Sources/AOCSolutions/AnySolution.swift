import AOCKit

public struct AnySolution {
    public let part1: (Input) throws -> CustomStringConvertible
    public let part2: (Input) throws -> CustomStringConvertible
    
    init<S: Solution>(_ solution: S) {
        self.part1 = { input in
            return try solution.part1(solution.transformInput(input))
        }
        
        self.part2 = { input in
            return try solution.part2(solution.transformInput(input))
        }
    }
}
