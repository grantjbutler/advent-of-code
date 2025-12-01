import Foundation

public struct Day: Hashable, Sendable {
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
        let month = day < 25 ? 12 : 11
        let components = DateComponents(year: year, month: month, day: day)
        let now = Calendar.adventOfCode.date(from: components)!
        let nextDate = Calendar.adventOfCode.date(byAdding: .day, value: 1, to: now)!
        let nextDateComponents = Calendar.adventOfCode.dateComponents([.day, .year], from: nextDate)
        return Day(year: nextDateComponents.year!, day: nextDateComponents.day!)
    }
}
