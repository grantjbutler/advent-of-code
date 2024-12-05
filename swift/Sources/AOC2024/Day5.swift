import AOCKit

public enum Day5: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let (mapping, instructions) = try InputParser().parse(input[...])
        
        let filteredPrintings = instructions.filter { instructions in
            for (index, instruction) in instructions.indexed() {
                guard let rules = mapping.rules[instruction] else { return false }
                
                let before = Set(instructions[..<index])
                let after = Set(instructions[instructions.index(after: index)...])
                
                guard before.subtracting(rules.before).isEmpty else { return false }
                guard after.subtracting(rules.after).isEmpty else { return false }
            }
            return true
        }
        
        return filteredPrintings.map { printing in
            return printing[printing.count / 2]
        }
        .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let (mapping, instructions) = try InputParser().parse(input[...])
        
        let filteredPrintings = instructions.filter { instructions in
            for (index, instruction) in instructions.indexed() {
                guard let rules = mapping.rules[instruction] else { return false }
                
                let before = Set(instructions[..<index])
                let after = Set(instructions[instructions.index(after: index)...])
                
                guard before.subtracting(rules.before).isEmpty else { return true }
                guard after.subtracting(rules.after).isEmpty else { return true }
            }
            return false
        }
        
        return filteredPrintings.map { printing in
            return printing.sorted { a, b in
                guard let aRules = mapping.rules[a] else { return false }
                
                return aRules.after.contains(b)
            }
        }
        .map { printing in
            return printing[printing.count / 2]
        }
        .sum()
    }
}

private struct Rules: Hashable {
    struct Entry: Hashable {
        var before: Set<Int> = []
        var after: Set<Int> = []
    }
    
    var rules: [Int: Entry] = [:]
    
    mutating func addRule(page: Int, after: Int) {
        rules[page, default: Entry()].after.insert(after)
        rules[after, default: Entry()].before.insert(page)
    }
}

private struct RuleParser: Parser {
    var body: some Parser<Substring.UTF8View, (Int, Int)> {
        Int.parser()
        "|".utf8
        Int.parser()
    }
}

private struct RulesParser: Parser {
    var body: some Parser<Substring, Rules> {
        Many(into: Rules()) { mapping, pair in
            mapping.addRule(page: pair.0, after: pair.1)
        } element: {
            RuleParser()
        } separator: {
            Whitespace(1, .vertical)
        }
    }
}

private struct PrintingParser: Parser {
    var body: some Parser<Substring, [Int]> {
        Many {
            Int.parser()
        } separator: {
            ","
        }
    }
}

private struct InputParser: Parser {
    var body: some Parser<Substring, (Rules, [[Int]])> {
        RulesParser()
        
        Whitespace(2, .vertical)
        
        Many {
            PrintingParser()
        } separator: {
            Whitespace(1, .vertical)
        }
    }
}
