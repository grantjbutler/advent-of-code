import AOCKit
import Foundation

public enum Day4: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try input
            .lines
            .parse(using: CardParser())
            .map { arg in
                return arg.winningNumbers
                    .intersection(arg.cardNumbers)
                    .count
            }
            .map { count in
                guard count > 0 else { return 0 }
                return Int(pow(2.0, Double(count - 1)))
            }
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return try input
            .lines
            .parse(using: CardParser())
            .reduce(into: [Int: Int]()) { partialResult, card in
                let matches = card.winningNumbers
                    .intersection(card.cardNumbers)
                    .count
                
                partialResult[card.card, default: 0] += 1
                
                if matches > 0 {
                    for i in 1...matches {
                        partialResult[card.card + i, default: 0] += partialResult[card.card, default: 0]
                    }
                }
            }
            .sum(\.value)
    }
}

private struct CardParser: Parser {
    var body: some Parser<Substring, (card: Int, winningNumbers: Set<Int>, cardNumbers: Set<Int>)> {
        Parse { card, winningNumbers, cardNumbers in
            return (card: card, winningNumbers: Set(winningNumbers), cardNumbers: Set(cardNumbers))
        } with: {
            "Card"
            Whitespace()
            Digits()
            ":"
            
            Whitespace()
            
            IntegersParser()
            
            Whitespace()
            
            "|"
            
            Whitespace()
            
            IntegersParser()
        }
    }
}
