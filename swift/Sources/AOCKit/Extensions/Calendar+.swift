import Foundation

extension Calendar {
    public static var adventOfCode: Calendar {
        var calendar = Calendar(identifier: .gregorian)
        calendar.timeZone = .init(identifier: "America/New_York")!
        return calendar
    }
}
