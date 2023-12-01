import ArgumentParser
import Foundation

struct Fetch: AsyncParsableCommand {
    static var configuration = CommandConfiguration(
        abstract: "Handles fetching inputs.",
        subcommands: [Fetch.Input.self, Fetch.SetCookie.self]
    )
    
    static let cookiesURL = FileManager.default.homeDirectoryForCurrentUser
        .appending(components: ".config", "advent-of-code", "cookies", directoryHint: .notDirectory)
}
