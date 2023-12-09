import AOCKit

private enum Direction: String, CaseIterable {
    case left = "L"
    case right = "R"
}

private func gcd(_ m: Int, _ n: Int) -> Int {
  var a = 0
  var b = max(m, n)
  var r = min(m, n)

  while r != 0 {
    a = b
    b = r
    r = a % b
  }
  return b
}

private func lcm(_ m: Int, _ n: Int) -> Int {
  return m*n / gcd(m, n)
}

public enum Day8: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let (instructions, nodes) = try instructionsParser
            .parse(input[...])
        
        let graph = nodes.reduce(into: [Substring: (left: Substring, right: Substring)]()) { graph, node in
            graph[node.node] = (left: node.left, right: node.right)
        }
        
        return solveMaze(instructions: instructions, graph: graph, start: "AAA"[...], sentinel: { $0 == "ZZZ"})
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let (instructions, nodes) = try instructionsParser
            .parse(input[...])
        
        let graph = nodes.reduce(into: [Substring: (left: Substring, right: Substring)]()) { graph, node in
            graph[node.node] = (left: node.left, right: node.right)
        }
        
        return graph
            .keys
            .filter { $0.hasSuffix("A") }
            .map { startingNode in
                solveMaze(instructions: instructions, graph: graph, start: startingNode, sentinel: { $0.hasSuffix("Z") })
            }
            .uniqued()
            .reduce(into: 1) { partialResult, number in
                partialResult = lcm(partialResult, number)
            }
    }
    
    private static func solveMaze(instructions: [Direction], graph: [Substring: (left: Substring, right: Substring)], start: Substring, sentinel: (Substring) -> Bool) -> Int {
        var count = 0
        var current = start
        
        for instruction in instructions.cycled() {
            let (left, right) = graph[current]!
            
            if current == left && current == right { fatalError("Found a loop!") }
            
            switch instruction {
            case .left: current = left
            case .right: current = right
            }
            
            count += 1
            
            if sentinel(current) { break }
        }
        
        return count
    }
}

private let nodeParser = Parse(input: Substring.self) {
    (node: $0, left: $1, right: $2)
} with: {
    Prefix(3...3, while: { $0.isLetter || $0.isNumber })
    
    Whitespace()
    
    "="
    
    Whitespace()
    
    "("
    Prefix(3...3, while: { $0.isLetter || $0.isNumber })
    ","
    Whitespace()
    Prefix(3...3, while: { $0.isLetter || $0.isNumber })
    ")"
}

private let instructionsParser = Parse(input: Substring.self) {
    Many {
        Direction.parser()
    }
    
    Whitespace()
    
    Many {
        nodeParser
    } separator: {
        Whitespace()
    }
}
