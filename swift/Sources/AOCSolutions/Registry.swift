import AOCKit
import AOC2022
import Foundation

public enum Registry {
    private static var solutions: [Day: AnySolution] = [
        .init(year: 2022, day: 1): .init(AOC2022.Day1()),
        .init(year: 2022, day: 2): .init(AOC2022.Day2()),
        .init(year: 2022, day: 3): .init(AOC2022.Day3()),
    ]
    private static var inputs: [Int: Bundle] = [
        2022: AOC2022.Resources.bundle
    ]
    
    public static func solution(for day: Day) -> AnySolution? {
        return solutions[day]
    }
    
    public static func inputBundle(for year: Int) -> Bundle? {
        return inputs[year]
    }
}
