import ArgumentParser
import AOCKit
import Foundation
import AppKit

extension Fetch {
    struct Input: AsyncParsableCommand {
        static var configuration = CommandConfiguration(
            commandName: "input",
            abstract: "Fetches the input for a given day."
        )
        
        @Argument
        var day: Day = .today
        
        @Flag
        var wait: Bool = false
        
        @Flag
        var open: Bool = false
        
        mutating func run() async throws {
            guard FileManager.default.fileExists(atPath: Fetch.cookiesURL.path(percentEncoded: false)),
                FileManager.default.isReadableFile(atPath: Fetch.cookiesURL.path(percentEncoded: false)) else {
                throw FetchError.noSessionCookie
            }
            
            if wait {
                day = day.nextDay()
            }
            
            print("Fetching input for \(day.day), \(day.year)")
            
            let input = try await fetchInput()
            try writeInput(input)
            
            if open {
                openWebsite()
            }
            
            if wait {
                try await beep()
            }
        }
        
        private func fetchInput() async throws -> Data {
            if wait {
                guard let tomorrow = Calendar.adventOfCode.date(byAdding: .day, value: 1, to: Calendar.adventOfCode.startOfDay(for: .now)) else {
                    throw FetchError.couldNotCalculateTomorrow
                }
                
                print("Waiting until input is available, \(tomorrow.formatted(.relative(presentation: .numeric)))...")
                
                try await Task.sleep(for: .seconds(tomorrow.timeIntervalSinceNow))
            }
            
            let encodedCookies = try Data(contentsOf: Fetch.cookiesURL)
            guard let cookies = try JSONSerialization.jsonObject(with: encodedCookies) as? [[HTTPCookiePropertyKey: Any]] else {
                throw FetchError.couldNotLoadCookies
            }
            
            var request = URLRequest(url: URL(string: "https://adventofcode.com/\(day.year)/day/\(day.day)/input")!)
            HTTPCookie.requestHeaderFields(with: cookies.compactMap(HTTPCookie.init(properties:))).forEach { pair in
                request.addValue(pair.value, forHTTPHeaderField: pair.key)
            }
            
            let (data, response) = try await URLSession.shared.data(for: request)
            guard let httpResponse = response as? HTTPURLResponse else {
                throw FetchError.invalidResponse
            }
            
            guard httpResponse.statusCode >= 200, httpResponse.statusCode <= 299 else {
                throw FetchError.invalidResponse
            }
            
            return data
        }
        
        private func writeInput(_ data: Data) throws {
            let folder = AOC.rootDir
                .appending(components: "AOC\(day.year)", "Inputs", "Day\(day.day)", directoryHint: .isDirectory)
            
            try FileManager.default.createDirectory(at: folder, withIntermediateDirectories: true)
            
            let inputFile = folder.appending(component: "input.txt", directoryHint: .notDirectory)
            try data.write(to: inputFile)
        }
        
        private func openWebsite() {
            NSWorkspace.shared.open(URL(string: "https://adventofcode.com/\(day.year)/day/\(day.day)")!)
        }
        
        private func beep() async throws {
            for _ in 0 ..< 5 {
                NSSound.beep()
                
                try await Task.sleep(for: .milliseconds(500))
            }
        }
    }
}

enum FetchError: Error {
    case noSessionCookie
    case couldNotCalculateTomorrow
    case couldNotLoadCookies
    case invalidResponse
}
