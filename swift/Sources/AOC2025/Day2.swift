import AOCKit
import Foundation

public enum Day2: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let ids = try IDParser().parse(input[...])
        
        return ids.filter { value in
            let stringValue = String(value)
            return isRepeatedString(stringValue, chunk: stringValue.count / 2)
        }
        .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let ids = try IDParser().parse(input[...])
        
        return ids.filter { value in
            let stringValue = String(value)
            
            for chunk in (1..<stringValue.count).reversed() {
                if isRepeatedString(stringValue, chunk: chunk) {
                    return true
                }
            }
            
            return false
        }
        .sum()
    }
    
    private static func isRepeatedString(_ string: String, chunk: Int) -> Bool {
        if chunk == 0 { return false }
        guard string.count > 1 else { return false }
        
        let (count, remainder) = string.count.quotientAndRemainder(dividingBy: chunk)
        guard remainder == 0 else { return false }
        
        let firstChunk = string[string.startIndex..<string.index(string.startIndex, offsetBy: chunk)]
        return string == String(repeating: String(firstChunk), count: count)
    }
}

private struct IDParser: Parser {
    var body: some Parser<Substring, IndexSet> {
        Many {
            Digits()
            
            "-"
            
            Digits()
        } separator: {
            ","
        }
        .map { pairs in
            var indexSet = IndexSet()
            
            pairs.forEach { start, end in
                indexSet.insert(integersIn: ClosedRange(uncheckedBounds: (lower: start, upper: end)))
            }
            
            return indexSet
        }
    }
}
