import AOCKit
import Foundation

public struct AnySolution {
    public let run: (String) throws -> Void
    
    init<S: Solution>(_ solution: S) {
        self.run = { input in
            let solutionInput = try solution.transformInput(input)
            
            try runSolution("Part 1") {
                try solution.part1(solutionInput)
            }
            
            try runSolution("Part 2") {
                try solution.part2(solutionInput)
            }
        }
    }
}

private func runSolution<T>(_ name: String, _ block: () throws -> T) rethrows {
    let (result, time) = try measure(block)
    
    print("[\(name)] Result: \(result)")
    print("[\(name)] Executed in \(time.formatted(.units(allowed: [.seconds], fractionalPart: .show(length: 4)))).")
}

private func measure<T>(_ block: () throws -> T) rethrows -> (T, Duration) {
    let clock = ContinuousClock()
    
    var result: T?
    let duration = try clock.measure {
        result = try block()
    }

    return (result!, duration)
}
