import AOCKit
import AOC2022
import AOC2023
import AOC2024
import AOC2025
import Foundation

public enum Registry {
    private static let solutions: [Day: AnySolution] = [
        .init(year: 2022, day: 1): .init(AOC2022.Day1.self),
        .init(year: 2022, day: 2): .init(AOC2022.Day2.self),
        .init(year: 2022, day: 3): .init(AOC2022.Day3.self),
        
        .init(year: 2023, day: 1): .init(AOC2023.Day1.self),
        .init(year: 2023, day: 2): .init(AOC2023.Day2.self),
        .init(year: 2023, day: 3): .init(AOC2023.Day3.self),
        .init(year: 2023, day: 4): .init(AOC2023.Day4.self),
        .init(year: 2023, day: 5): .init(AOC2023.Day5.self),
        .init(year: 2023, day: 6): .init(AOC2023.Day6.self),
        .init(year: 2023, day: 7): .init(AOC2023.Day7.self),
        .init(year: 2023, day: 8): .init(AOC2023.Day8.self),
        .init(year: 2023, day: 9): .init(AOC2023.Day9.self),
        
        .init(year: 2024, day: 1): .init(AOC2024.Day1.self),
        .init(year: 2024, day: 2): .init(AOC2024.Day2.self),
        .init(year: 2024, day: 3): .init(AOC2024.Day3.self),
        .init(year: 2024, day: 4): .init(AOC2024.Day4.self),
        .init(year: 2024, day: 5): .init(AOC2024.Day5.self),
        .init(year: 2024, day: 6): .init(AOC2024.Day6.self),
        .init(year: 2024, day: 7): .init(AOC2024.Day7.self),
        
        .init(year: 2025, day: 1): .init(AOC2025.Day1.self),
        .init(year: 2025, day: 2): .init(AOC2025.Day2.self),
    ]
    private static let inputs: [Int: Bundle] = [
        2022: AOC2022.Resources.bundle,
        2023: AOC2023.Resources.bundle,
        2024: AOC2024.Resources.bundle,
        2025: AOC2025.Resources.bundle,
    ]
    
    public static func solution(for day: Day) -> AnySolution? {
        return solutions[day]
    }
    
    public static func inputBundle(for year: Int) -> Bundle? {
        return inputs[year]
    }
}
