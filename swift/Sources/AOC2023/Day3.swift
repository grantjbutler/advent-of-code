import AOCKit
import Foundation

struct Part {
    var id = UUID()
    var number: String = ""
    var coordinates: [Point] = []
}

struct Point: Hashable {
    let x: Int
    let y: Int
}

public struct Day3: Solution {
    public init() {}

    public func part1(_ input: String) -> some CustomStringConvertible {
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
        
        let symbolPoints = input
            .lines
            .indexed()
            .flatMap { index, line in
                line
                    .enumerated()
                    .split(whereSeparator: { $0.element.isNumber || $0.element == "." })
                    .flatMap { slice in
                        slice.map { pair in
                            Point(x: pair.offset, y: index)
                        }
                    }
            }
        
        return symbolPoints
            .flatMap { point in
                find(parts: parts, center: point)
            }
            .map(\.number)
            .asIntegers
            .sum()
    }
    
    public func part2(_ input: String) -> some CustomStringConvertible {
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
        
        let symbolPoints = input
            .lines
            .indexed()
            .flatMap { index, line in
                line
                    .enumerated()
                    .split(whereSeparator: { $0.element != "*" })
                    .flatMap { slice in
                        slice.map { pair in
                            Point(x: pair.offset, y: index)
                        }
                    }
            }
        
        return symbolPoints
            .compactMap { point -> Int? in
                let parts = find(parts: parts, center: point)
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
