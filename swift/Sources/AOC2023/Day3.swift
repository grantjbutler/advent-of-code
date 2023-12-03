import AOCKit
import Foundation

public struct Part {
    var id = UUID()
    var number: String = ""
    var coordinates: [Point] = []
}

public struct Symbol {
    let token: String
    let coordinate: Point
}

public enum Day3: Solution {
    public typealias SolutionInput = (parts: [Part], symbols: [Symbol])

    public static func transformInput(_ input: String) throws -> SolutionInput {
        let parts = input
            .lines
            .indexed()
            .flatMap { index, line in
                line
                    .enumerated()
                    .split(whereSeparator: { !$0.element.isNumber })
                    .map { slice in
                        slice.reduce(into: Part()) { partialResult, pair in
                            partialResult.number.append(contentsOf: String(pair.element))
                            partialResult.coordinates.append(Point(x: pair.offset, y: index))
                        }
                    }
            }
        
        let symbols = input
            .lines
            .indexed()
            .flatMap { index, line in
                line
                    .enumerated()
                    .split(whereSeparator: { $0.element.isNumber || $0.element == "." })
                    .flatMap { slice in
                        slice.map { pair in
                            Symbol(token: String(pair.element), coordinate: Point(x: pair.offset, y: index))
                        }
                    }
            }
        
        return (parts: parts, symbols: symbols)
    }

    public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
        return input.symbols
            .flatMap { symbol in
                find(parts: input.parts, center: symbol.coordinate)
            }
            .map(\.number)
            .asIntegers
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
        return input.symbols
            .filter { $0.token == "*" }
            .compactMap { symbol -> Int? in
                let parts = find(parts: input.parts, center: symbol.coordinate)
                guard parts.count == 2 else { return nil }
                
                return parts
                    .map(\.number)
                    .asIntegers
                    .product()
            }
            .sum()
    }
}

private func find(parts: [Part], center: Point) -> [Part] {
    product([center.x - 1, center.x, center.x + 1], [center.y - 1, center.y, center.y + 1])
        .map { pair in
            return Point(x: pair.0, y: pair.1)
        }
        .compactMap { coordinate in
            parts.first(where: { $0.coordinates.contains(coordinate) })
        }
        .uniqued(on: \.id)
}
