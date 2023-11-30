import AOCKit
import ArgumentParser

extension Day: ExpressibleByArgument {
    public init?(argument: String) {
        let components = argument.split(separator: ":", maxSplits: 1)
        guard components.count == 2 else { return nil }
        
        guard let year = Int(components[0]) else { return nil }
        guard let day = Int(components[1]) else { return nil }
        
        self.init(year: year, day: day)
    }
}
