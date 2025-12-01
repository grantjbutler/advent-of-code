import AOCKit
import Foundation
import RegexBuilder

public enum Day3: Solution {
    public typealias SolutionInput = String

    public static func part1(_ input: SolutionInput) throws -> some CustomStringConvertible {
        return input
            .lines
            .map { line in
                return line.matches(of: Regex {
                    "mul("
                    Capture {
                        OneOrMore(CharacterClass(.digit))
                    }
                    ","
                    Capture {
                        OneOrMore(CharacterClass(.digit))
                    }
                    ")"
                })
                .compactMap { match -> Int? in
                    guard let a = Int(match.output.1) else { return nil }
                    guard let b = Int(match.output.2) else { return nil }
                    return a * b
                }
                .sum()
            }
            .sum()
    }
    
    public static func part2(_ input: SolutionInput) throws -> some CustomStringConvertible {
        var enabled = true
        
        let instructions = try input.lines
            .parse(using: TokensParser())
            .flatMap(\.self)
        
        return instructions.reduce(into: 0) { result, instruction in
            switch instruction {
            case .enable:
                enabled = true
            case .disable:
                enabled = false
            case let .mul(a, b):
                if enabled {
                    result += a * b
                }
            case .skipped:
                break
            }
        }
    }
}

private enum Token: Equatable {
    case enable
    case disable
    case skipped
    case mul(Int, Int)
}

private struct TokenParser: Parser {
    var body: some Parser<Substring, Token> {
        OneOf {
            Parse {
                "mul("
                Int.parser()
                ","
                Int.parser()
                ")"
            }
            .map(.case(Token.mul))
            
            "don't()"
                .map(.case(Token.disable))
            
            "do()"
                .map(.case(Token.enable))
            
            First()
                .map { _ in Token.skipped }
        }
    }
}

private struct TokensParser: Parser {
    var body: some Parser<Substring, [Token]> {
        Many(into: [Token]()) { result, token in
            if token == Token.skipped { return }
            
            result.append(token)
        } element: {
            TokenParser()
        } terminator: {
            End()
        }
    }
}
