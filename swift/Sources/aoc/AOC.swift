import ArgumentParser
import Foundation

@main
struct AOC: AsyncParsableCommand {
    static let configuration = CommandConfiguration(
        abstract: "CLI for working with Advent of Code",
        subcommands: [Run.self, Fetch.self, Make.self],
        defaultSubcommand: Run.self
    )
    
    static let rootDir = URL(filePath: #filePath, directoryHint: .notDirectory) // Sources/aoc/AOC.swift
            .deletingLastPathComponent() // Sources/aoc/
            .deletingLastPathComponent() // Sources/
}
