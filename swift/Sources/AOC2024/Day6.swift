import AOCKit

public enum Day6: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
        var state = State(map: input)
        state.run()
        return state.visitedPoints.count
    }
    
    public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
        return ""
    }
}

private struct State {
    private var obstacles: Set<Point> = []
    private var guardPosition: Point = .zero
    private var guardDirection = Direction.north
    private(set) var visitedPoints: Dictionary<Point, Set<Direction>> = [:]
    private let maxPosition: Point
    
    init(map: String) {
        let rows = map.lines.filter { !$0.isEmpty }
        for (index, row) in rows.indexed() {
            for (character, column) in zip(row, 0...) {
                if character == "#" {
                    obstacles.insert(Point(x: column, y: index))
                } else if character == "^" {
                    guardPosition = Point(x: column, y: index)
                }
            }
        }
        
        visitedPoints[guardPosition, default: Set()].insert(guardDirection)
        
        maxPosition = Point(x: rows.last!.count - 1, y: rows.count - 1)
    }
    
    mutating func run() {
        while true {
            let nextPosition = guardPosition.point(offsetBy: 1, inDirection: guardDirection)
            
            if let visitedPoint = visitedPoints[nextPosition], visitedPoint.contains(guardDirection) {
                return
            }
            
            if obstacles.contains(nextPosition) {
                guardDirection = guardDirection.rotatedRight
            } else if nextPosition.x > maxPosition.x || nextPosition.x < 0 || nextPosition.y > maxPosition.y || nextPosition.y < 0 {
                return
            } else {
                visitedPoints[nextPosition, default: Set()].insert(guardDirection)
                guardPosition = nextPosition
            }
        }
    }
}
