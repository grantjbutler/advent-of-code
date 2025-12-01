public protocol Solution: Sendable {
    associatedtype SolutionInput = String
    associatedtype Part1Output: CustomStringConvertible
    associatedtype Part2Output: CustomStringConvertible
    
    static func transformInput(_ input: String) throws -> SolutionInput
    
    static func part1(_ input: SolutionInput) throws -> Part1Output
    static func part2(_ input: SolutionInput) throws -> Part2Output
}

public extension Solution where SolutionInput == String {
    static func transformInput(_ input: String) -> SolutionInput {
        input
    }
}
