public protocol Solution {
    associatedtype SolutionInput = String
    associatedtype Part1Output: CustomStringConvertible
    associatedtype Part2Output: CustomStringConvertible
    
    func transformInput(_ input: String) throws -> SolutionInput
    
    func part1(_ input: SolutionInput) throws -> Part1Output
    func part2(_ input: SolutionInput) throws -> Part2Output
}

public extension Solution where SolutionInput == String {
    func transformInput(_ input: String) -> SolutionInput {
        input
    }
}
