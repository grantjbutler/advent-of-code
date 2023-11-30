import ArgumentParser
import Foundation

public struct Day: Hashable {
    public let year: Int
    public let day: Int
    
    public init(year: Int, day: Int) {
        self.year = year
        self.day = day
    }
}

extension Day {
    public static var today: Day {
        let components = Calendar.adventOfCode.dateComponents([.day, .year], from: .init())
        return Day(year: components.year!, day: components.day!)
    }
    
    public func nextDay() -> Day {
        return .init(year: year, day: day + 1)
    }
}
