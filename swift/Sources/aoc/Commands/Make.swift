import AOCKit
import ArgumentParser
import Foundation

struct Make: AsyncParsableCommand {
    static var configuration = CommandConfiguration(
        abstract: "Creates the basic structure for a day's problem."
    )

    @Argument
    var day: Day = .today
    
    func run() async throws {
        let folderURL = URL(filePath: #filePath, directoryHint: .notDirectory) // Sources/aoc/Commands/Make.swift
            .deletingLastPathComponent() // Sources/aoc/Commands/
            .deletingLastPathComponent() // Sources/aoc/
            .deletingLastPathComponent() // Sources/
            .appending(component: "AOC\(day.year)", directoryHint: .isDirectory)
        
        let fileURL = folderURL.appending(component: "Day\(day.day).swift", directoryHint: .notDirectory)
        
        if FileManager.default.fileExists(atPath: fileURL.path(percentEncoded: false)) {
            print("File already exists for day \(day.day). Skipping creation.")
            return
        }
        
        try? FileManager.default.createDirectory(at: folderURL, withIntermediateDirectories: true)
        
        try template(day).write(to: fileURL, atomically: true, encoding: .utf8)
        
        print("Successfully created file for day \(day.day).")
    }
}

private func template(_ day: Day) -> String {
    return """
    import AOCKit

    public struct Day\(day.day): Solution {
        public init() {}

        public func part1(_ input: Input) -> String {
            return ""
        }
        
        public func part2(_ input: Input) -> String {
            return ""
        }
    }
    """
}
