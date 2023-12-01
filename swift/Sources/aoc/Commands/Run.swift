import ArgumentParser
import AOCKit
import AOCSolutions
import Foundation

struct Run: AsyncParsableCommand {
    static var configuration = CommandConfiguration(abstract: "Runs the solution for a given day.")
    
    @Argument
    var day: Day = .today
    
    @Flag
    var test: Bool = false
    
    mutating func run() async throws {
        print("Running solution for Day \(day.day), \(day.year).")
        
        let input = try createInput()
        guard let solution = Registry.solution(for: day) else {
            throw RunError.couldNotCreateSolution
        }
        
        try solution.run(input)
    }
    
    private func createInput() throws -> String {
        guard let bundle = Registry.inputBundle(for: day.year) else {
            throw RunError.couldNotLoadInput
        }
        let filename = test ? "test" : "input"
        guard let resourceURL = bundle.url(forResource: "Inputs/Day\(day.day)/\(filename)", withExtension: "txt") else {
            throw RunError.couldNotLoadInput
        }
        return try String(contentsOf: resourceURL).trimmingCharacters(in: .newlines) // Xcode has a habit of adding new lines to the end of files, so let's get rid of that.
    }
}

enum RunError: Error {
    case couldNotLoadInput
    case couldNotCreateSolution
}
