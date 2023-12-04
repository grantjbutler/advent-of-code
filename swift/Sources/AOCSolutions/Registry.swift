import AOCKit
import AOC2022
import AOC2023
import Foundation

public enum Registry {
    private static var solutions: [Day: AnySolution] = [
        .init(year: 2022, day: 1): .init(AOC2022.Day1.self),
        .init(year: 2022, day: 2): .init(AOC2022.Day2.self),
        .init(year: 2022, day: 3): .init(AOC2022.Day3.self),
        
        .init(year: 2023, day: 1): .init(AOC2023.Day1.self),
        .init(year: 2023, day: 2): .init(AOC2023.Day2.self),
        .init(year: 2023, day: 3): .init(AOC2023.Day3.self),
        .init(year: 2023, day: 4): .init(AOC2023.Day4.self),
    ]
    private static var inputs: [Int: Bundle] = [
        2022: AOC2022.Resources.bundle,
        2023: AOC2023.Resources.bundle
    ]
    
    public static func solution(for day: Day) -> AnySolution? {
        return solutions[day]
    }
    
    public static func inputBundle(for year: Int) -> Bundle? {
        return inputs[year]
    }
}
