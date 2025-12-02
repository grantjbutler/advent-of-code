import AOCKit
import Foundation

public enum Day2: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let ids = try IDParser().parse(input[...])
        
        return ids.filter { value in
            let stringValue = String(value)
            guard stringValue.count % 2 == 0 else { return false }
            
            let firstHalf = stringValue[stringValue.startIndex..<stringValue.index(stringValue.startIndex, offsetBy: stringValue.count / 2)]
            let secondHalf = stringValue[stringValue.index(stringValue.startIndex, offsetBy: stringValue.count / 2)..<stringValue.endIndex]
            
            return firstHalf == secondHalf
        }
        .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        let ids = try IDParser().parse(input[...])
        
        return ids.filter { value in
            let stringValue = String(value)
            
            for chunk in (1..<stringValue.count).reversed() {
                let (count, remainder) = stringValue.count.quotientAndRemainder(dividingBy: chunk)
                guard remainder == 0 else { continue }
                
                let firstChunk = stringValue[stringValue.startIndex..<stringValue.index(stringValue.startIndex, offsetBy: chunk)]
                if stringValue == String(repeating: String(firstChunk), count: count) {
                    return true
                }
            }
            
            return false
        }
        .sum()
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
