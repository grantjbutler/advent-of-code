public protocol Solution {
    associatedtype SolutionInput = Input
    associatedtype Part1Output: CustomStringConvertible
    associatedtype Part2Output: CustomStringConvertible
    
    func transformInput(_ input: Input) throws -> SolutionInput
    
    func part1(_ input: SolutionInput) throws -> Part1Output
    func part2(_ input: SolutionInput) throws -> Part2Output
}

public extension Solution where SolutionInput == Input {
    func transformInput(_ input: Input) -> SolutionInput {
        input
    }
}
