import ArgumentParser
import Foundation

extension Fetch {
    struct SetCookie: AsyncParsableCommand {
        static var configuration = CommandConfiguration(
            commandName: "set-cookie",
            abstract: "Sets the cookie to use for fetching inputs."
        )
        
        @Argument
        var cookie: String
        
        func run() async throws {
            let properties: [HTTPCookiePropertyKey: Any] = [
                .domain: ".adventofcode.com",
                .name: "session",
                .value: cookie,
                .secure: true,
                .init("HttpOnly"): true
            ]
            
            try? FileManager.default.createDirectory(at: Fetch.cookiesURL.deletingLastPathComponent(), withIntermediateDirectories: true)
            
            let cookieData = try JSONSerialization.data(withJSONObject: [properties])
            try cookieData.write(to: Fetch.cookiesURL)
            
            print("Cookie saved!")
        }
    }
}
