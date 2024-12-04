import AOCKit

public enum Day4: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) -> some CustomStringConvertible {
        let matrix = input
            .lines
            .map { line in
                Array(line)
            }
        
        let xIndexes = matrix.indexed().flatMap { row in
            let indicies = row.element.indexed().reduce(into: [Int]()) { partialResult, pair in
                guard pair.element == "X" else { return }
                partialResult.append(pair.index)
            }
            
            return indicies.map { Point(x: $0, y: row.index) }
        }
        
        return xIndexes.reduce(into: 0) { count, point in
            let characters = [
                [Point(x: point.x - 1, y: point.y), Point(x: point.x - 2, y: point.y), Point(x: point.x - 3, y: point.y)],
                [Point(x: point.x + 1, y: point.y), Point(x: point.x + 2, y: point.y), Point(x: point.x + 3, y: point.y)],
                [Point(x: point.x, y: point.y - 1), Point(x: point.x, y: point.y - 2), Point(x: point.x, y: point.y - 3)],
                [Point(x: point.x, y: point.y + 1), Point(x: point.x, y: point.y + 2), Point(x: point.x, y: point.y + 3)],
                
                [Point(x: point.x - 1, y: point.y - 1), Point(x: point.x - 2, y: point.y - 2), Point(x: point.x - 3, y: point.y - 3)],
                [Point(x: point.x + 1, y: point.y + 1), Point(x: point.x + 2, y: point.y + 2), Point(x: point.x + 3, y: point.y + 3)],
                [Point(x: point.x + 1, y: point.y - 1), Point(x: point.x + 2, y: point.y - 2), Point(x: point.x + 3, y: point.y - 3)],
                [Point(x: point.x - 1, y: point.y + 1), Point(x: point.x - 2, y: point.y + 2), Point(x: point.x - 3, y: point.y + 3)],
            ]
            
            count += characters.count(where: { points in
                guard points.allSatisfy({ point in
                    return matrix.indices.contains(point.y) && matrix[point.y].indices.contains(point.x)
                }) else { return false }
                
                return matrix[points[0].y][points[0].x] == "M"
                    && matrix[points[1].y][points[1].x] == "A"
                    && matrix[points[2].y][points[2].x] == "S"
            })
        }
    }
    
    public static func part2(_ input: SolutionInput) -> some CustomStringConvertible {
        let matrix = input
            .lines
            .map { line in
                Array(line)
            }
        
        let xIndexes = matrix.indexed().flatMap { row in
            let indicies = row.element.indexed().reduce(into: [Int]()) { partialResult, pair in
                guard pair.element == "A" else { return }
                partialResult.append(pair.index)
            }
            
            return indicies.map { Point(x: $0, y: row.index) }
        }
        
        return xIndexes.reduce(into: 0) { count, point in
            let characters = [
                [Point(x: point.x - 1, y: point.y - 1), Point(x: point.x + 1, y: point.y + 1)],
                [Point(x: point.x - 1, y: point.y + 1), Point(x: point.x + 1, y: point.y - 1)]
            ]
            
            guard characters.allSatisfy({ points in
                guard points.allSatisfy({ point in
                    return matrix.indices.contains(point.y) && matrix[point.y].indices.contains(point.x)
                }) else { return false }
                
                return (matrix[points[0].y][points[0].x] == "M"
                    && matrix[points[1].y][points[1].x] == "S")
                    || (matrix[points[1].y][points[1].x] == "M"
                    && matrix[points[0].y][points[0].x] == "S")
            }) else { return }
            
            count += 1
        }
    }
}
