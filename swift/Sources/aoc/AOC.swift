import ArgumentParser

@main
struct AOC: AsyncParsableCommand {
    static var configuration = CommandConfiguration(
        abstract: "CLI for working with Advent of Code",
        subcommands: [Run.self, Fetch.self, Make.self]
    )
}
